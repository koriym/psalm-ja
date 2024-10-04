# Psalmでの型付け

PsalmはすべてのPHPDoc型アノテーションを解釈し、それらを使用してコードベースをさらに理解することができます。

型は、プロパティ、変数、関数パラメータ、および`return $x`に対して許容される値を記述するために使用されます。

## Docblock型構文

Psalmでは、docblockで複雑な型情報を表現することができます。

すべてのdocblock型は、[アトミック型](type_syntax/atomic_types.md)、[union型](type_syntax/union_types.md)、または[intersection型](type_syntax/intersection_types.md)のいずれかです。

さらに、PsalmはPHPDocの[型構文](https://docs.phpdoc.org/latest/guide/guides/types.html)、および[提案されているPHPDoc PSR型構文](https://github.com/php-fig/fig-standards/blob/master/proposed/phpdoc.md#appendix-a-types)をサポートしています。

詳細な説明は[Psalmでの型付け](typing_in_psalm.md)にあります。

## プロパティ宣言型 vs 代入型ヒント

`/** @var Type */`docblockを使用して、[プロパティ宣言](http://php.net/manual/en/language.oop5.properties.php)と変数の代入の両方にアノテーションを付けることができます。

### プロパティ宣言型

Psalmでクラスプロパティ宣言に特定の型を指定するには、`@var`宣言を使用できます：

```php
<?php
/** @var string|null */
public $foo;
```

`$this->foo = $some_variable;`をチェックする際、Psalmは`$some_variable`が`string`または`null`のいずれかであるかどうかを確認し、そうでない場合は問題を発生させます。

プロパティ型のdocblockを省略すると、Psalmは`MissingPropertyType`の問題を発生させます。

### 代入型ヒント

以下のコードを考えてみましょう：

```php
<?php
namespace YourCode {
  function bar() : int {
    $a = \ThirdParty\foo();
    return $a;
  }
}

namespace ThirdParty {
  function foo() {
    return mt_rand(0, 100);
  }
}
```

Psalmは、サードパーティの関数`ThirdParty\foo`が何を返すかわかりません。なぜなら、作者が戻り値の型を追加していないからです。関数が特定の値を返すことがわかっている場合は、次のように代入型ヒントを使用できます：

```php
<?php
namespace YourCode {
  function bar() : int {
    /** @var int */
    $a = \ThirdParty\foo();
    return $a;
  }
}

namespace ThirdParty {
  function foo() {
    return mt_rand(0, 100);
  }
}
```

これにより、Psalmは`$a`の可能な型が`int`であることを理解し、`return $a;`が整数を生成することを推論できるようになります。

ただし、プロパティ型とは異なり、代入型ヒントは拘束力がありません。Psalmが問題を発生させることなく、新しい代入によって上書きすることができます。例：

```php
<?php
/** @var string|null */
$a = foo();
$a = 6; // $aは今intとして型付けされています
```

特定の変数に対しても型ヒントを使用できます。例：

```php
<?php
/** @var string $a */
echo strpos($a, 'hello');
```

これはPsalmに`$a`が文字列であると仮定するように指示します（ただし、`$a`が未定義の場合はエラーをスローします）。

## 文字列/整数オプションの指定（別名 enums）

Psalmでは、特定の関数やメソッドに対して許可される文字列/整数値の特定のセットを指定できます。

以下のコードは[すべてのパスが値を返さないとPsalmが警告する](https://getpsalm.org/r/9f6f1ceab6)でしょう：

```php
<?php
function foo(string $s) : string {
  switch ($s) {
    case 'a':
      return 'hello';
    case 'b':
      return 'goodbye';
  }
}
```

`$s`のパラム型を`'a'|'b'`と指定すると、Psalmはすべてのパスが値を返すことを知ります：

```php
<?php
/** 
 * @param 'a'|'b' $s
 */
function foo(string $s) : string {
  switch ($s) {
    case 'a':
      return 'hello';
    case 'b':
      return 'goodbye';
  }
}
```

値がクラス定数にある場合でも、それらを使用できます：

```php
<?php
class A {
  const FOO = 'foo';
  const BAR = 'bar';
}

/** 
 * @param A::FOO | A::BAR $s
 */
function foo(string $s) : string {
  switch ($s) {
    case A::FOO:
      return 'hello';
    case A::BAR:
      return 'goodbye';
  }
}
```

クラス定数が共通のプレフィックスを共有している場合、ワイルドカードを使用してそれらをすべて指定できます：

```php
<?php
class A {
  const STATUS_FOO = 'foo';
  const STATUS_BAR = 'bar';
}

/** 
 * @param A::STATUS_* $s
 */
function foo(string $s) : string {
  switch ($s) {
    case A::STATUS_FOO:
      return 'hello';
    default:
      // その他のステータス
      return 'goodbye';
  }
}
```

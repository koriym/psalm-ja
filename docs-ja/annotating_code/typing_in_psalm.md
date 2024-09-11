# 詩篇のタイピング

Psalm はすべての PHPDoc 型注釈を解釈し、それを使ってコードベースをさらに理解することができます。

型は、プロパティや変数、関数のパラメータや`return $x` で使用可能な値を記述するために使用します。

## Docblock 型の構文

Psalmを使うと、docblockの中で多くの複雑な型情報を表現することができます。

すべてのdocblock型は[atomic types](type_syntax/atomic_types.md),[union types](type_syntax/union_types.md) または[intersection types](type_syntax/intersection_types.md) のいずれかです。

さらに、Psalm は PHPDoc の[type syntax](https://docs.phpdoc.org/latest/guide/guides/types.html) や[proposed PHPDoc PSR type syntax](https://github.com/php-fig/fig-standards/blob/master/proposed/phpdoc.md#appendix-a-types) もサポートしています。

## プロパティ宣言型 vs 代入型ヒント

`/** @var Type */` docblock を使って[property declarations](http://php.net/manual/en/language.oop5.properties.php) の両方にアノテーションをつけることができ、 Psalm が変数の代入を理解しやすくなります。

### プロパティ宣言型

Psalm では、`@var` 宣言を使用して、クラスのプロパティ宣言に特定の型を指定できます：

```php
<?php
/** @var string|null */
public $foo;
```

`$this->foo = $some_variable;` をチェックするとき、Psalm は`$some_variable` が`string` か`null` のどちらかであるかをチェックし、どちらでもない場合は問題を出します。

プロパティ・タイプ docblock を省略すると、Psalm は`MissingPropertyType` を発行します。

### 割り当てタイプヒント

次のコードを考えてみましょう：

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

Psalmはサードパーティーの関数`ThirdParty\foo` が何を返すのか知りません。もし関数が指定された値を返すことを知っていれば、次のように代入型ヒントを使うことができます：

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

これはPsalmに`int` が`$a` の可能な型であることを伝え、`return $a;` が整数を返すことを推測させます。

しかし、プロパティ型とは異なり、代入型ヒントはバインディングではありません。

```php
<?php
/** @var string|null */
$a = foo();
$a = 6; // $a is now typed as an int
```

特定の変数にタイプヒントを使うこともできます。

```php
<?php
/** @var string $a */
echo strpos($a, 'hello');
```

これは`$a` が文字列であると仮定するよう Psalm に指示します（ただし`$a` が未定義の場合はエラーを投げます）。

## 文字列/intオプションの指定（別名enum）

Psalmでは、与えられた関数やメソッドに対して、許容される文字列/int値の特定のセットを指定することができます。

この場合、Psalmは[complain that not all paths return a value](https://getpsalm.org/r/9f6f1ceab6) ：

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

`$s` の param type を`'a'|'b'` と指定すると、Psalm はすべてのパスが値を返すことを認識します：

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

もし値がクラス定数にあれば、それを使うこともできます：

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

クラス定数が共通の接頭辞を持つ場合は、ワイルドカードを使ってすべてを指定できます：

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
      // any other status
      return 'goodbye';
  }
}
```

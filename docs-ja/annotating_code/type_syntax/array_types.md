# 配列型

PHPでは、`array`型は一般的に3つの異なるデータ構造を表現するために使用されます：

[リスト](https://en.wikipedia.org/wiki/List_(abstract_data_type)):

```php
<?php
$a = [1, 2, 3, 4, 5];
```

[連想配列](https://en.wikipedia.org/wiki/Associative_array):

```php
<?php
$a = [0 => 'hello', 5 => 'goodbye'];
$b = ['a' => 'AA', 'b' => 'BB', 'c' => 'CC'];
```

代替[構造体](https://en.wikipedia.org/wiki/Struct_(C_programming_language)):

```php
<?php
$a = ['name' => 'Psalm', 'type' => 'tool'];
```

PHPは基本的にこれらの配列をすべて同じように扱います（ただし、最初のケースについては内部的に最適化が行われています）。

Psalmは型システムで配列を表現するためにいくつかの方法を持っています：

## ジェネリック配列

Psalmは[Javaから借用した構文](https://en.wikipedia.org/wiki/Generics_in_Java)を使用して、キーと値の両方の型を表記できます：

```php
/** @return array<TKey, TValue> */
```

また、`non-empty-array<TKey, TValue>`という特別な型を使用して、配列が空でないことを指定することもできます。

### PHPDoc構文

PHPDocでは、[以下の注釈で](https://docs.phpdoc.org/latest/guide/references/phpdoc/types.html#arrays)ジェネリック配列が保持する値の型を指定できます：

```php
/** @return ValueType[] */
```

Psalmでは、この注釈は`@psalm-return array<array-key, ValueType>`と同等です。

ジェネリック配列は、_連想配列_と_リスト_の両方を包含します。

## リスト

(Psalm 3.6以降)

Psalmは、`["red", "yellow", "blue"]`のような連続した整数インデックスの配列を表す`list`型をサポートしています。

リストを作成する一般的な方法は、`$arr[] =`表記を使用することです。

これらの配列は`array_is_list($arr)`(PHP 8.1以降)にtrueを返し、PHPアプリケーションでの配列使用の大部分を占めています。

`list`型は`list<SomeType>`の形式を取り、`SomeType`はPsalmがサポートする許可された[ユニオン型](union_types.md)です。

- `list`は`array<int, mixed>`のサブタイプです
- `list<Foo>`は`array<int, Foo>`のサブタイプです。

リスト型はいくつかの方法でその価値を示します：

```php
<?php
/**
 * @param array<int, string> $arr
 */
function takesArray(array $arr) : void {
  if ($arr) {
     // このインデックスは設定されていない可能性があります
    echo $arr[0];
  }
}

/**
 * @psalm-param list<string> $arr
 */
function takesList(array $arr) : void {
  if ($arr) {
    // リストのインデックスは常に0から始まるので、
    // 空でないリストはここに要素を持ちます
    echo $arr[0];
  }
}

takesArray(["hello"]); // これは問題ありません
takesArray([1 => "hello"]); // 警告なしにバグを引き起こす可能性があります

takesList(["hello"]); // これは問題ありません
takesList([1 => "hello"]); // Psalmで警告がトリガーされます
```

## 配列形状

Psalmは、キーオフセットが既知の配列に対して特別な形式をサポートしています：配列形状、別名「オブジェクトライクな配列」です。

次の配列が与えられた場合：

```php
<?php
["hello", "world", "foo" => new stdClass, 28 => false];
```

Psalmは内部的にこれを次のように型付けします：

```
array{0: string, 1: string, foo: stdClass, 28: false}
```

この形式で自分で型を指定することもできます。例：

```php
/** @return array{foo: string, bar: int} */
```

オプションのキーは末尾に`?`をつけて表すことができます。例：

```php
/** @return array{optional?: string, bar: int} */
```

(PHPに似た)「1行」コメントを使用できます。例：

```php
/** @return array { // コメント付きの配列。
 *     // コメントは独立した行に置くことができます。
 *     foo: string, // 配列キーの説明。
 *     bar: array {, // 別の配列キーの説明。
 *         'foo//bar': string, // 名前に"//"を含む配列キー。
 *     },
 * }
 */
```

ヒント：`InvalidArgument`の問題を避けるために同じ複雑な配列形状を何度もコピーしている場合は、代わりに[型エイリアス](utility_types.md#type-aliases)の使用を検討してください。

### 配列形状の検証

[Valinor](https://github.com/CuyZ/Valinor)を厳格モードで使用すると、Psalm配列形状構文を使用して実行時に配列形状を簡単にアサートできます（issetでキーを手動でアサートする代わりに）：

```php
try {
  $array = (new \CuyZ\Valinor\MapperBuilder())
      ->mapper()
      ->map(
          'array{a: string, b: int}',
          json_decode(file_get_contents('https://.../'), true)
      );

  /** @psalm-trace $array */; // array{a: string, b: int}

  echo $array['a'];
  echo $array['b'];
} catch (\CuyZ\Valinor\Mapper\MappingError $error) {
  // 何かを行う…
}
```

Valinorは、完全なPsalm構文サポートと他の多くの機能を備えた実行時と静的なPsalmアサーションの両方を提供します。詳細については[Valinorのドキュメント](https://valinor.cuyz.io/latest/)をご覧ください！

## リスト形状

Psalm 5以降、Psalmはキーオフセットが既知のリスト配列に対しても特別な形式をサポートしています。

次のリスト配列が与えられた場合：

```php
<?php
["hello", "world", new stdClass, false];
```

Psalmは内部的にこれを次のように型付けします：

```
list{string, string, stdClass, false}
```

この形式で自分で型を指定することもできます。例：

```php
/** @return list{string, int} */
/** @return list{0: string, 1: int} */
```

オプションのキーは、すべての要素にキーを指定し、オプションのキーに末尾`?`を指定することで表現できます。例：

```php
/** @return list{0: string, 1?: int} */
```

リスト形状は、[型理論の観点から](https://en.wikipedia.org/wiki/Tuple#Type_theory)本質的にn項組です。

## 未密閉配列およびリスト形状

Psalm v5以降、配列形状とリスト形状は、最後の要素として`...`を追加することでオープンとしてマークできます。

ここでは、オプションの配列を受け取る`handleOptions`関数があります。型は、既知の1つのキーが`string`型であり、潜在的に他の多くの未知の型のキーがあることを示しています。

```php
/** @param array{verbose: string, ...} $options */
function handleOptions(array $options): float {
    if ($options['verbose']) {
        var_dump($options);
    }
}

$options = get_opt(/* some code */);
$options['verbose'] = isset($options['verbose']);
handleOptions($options);
```

`...`は`...<array-key, mixed>`の省略形です。他の配列ジェネリック型を使用して、オープンな形状についてより多くの情報を提供できます。

```php
// これはオープンな配列です
/** @param array{someKey: string, ...} */ 
// これは次と同じです
/** @param array{someKey: string, ...<array-key, mixed>} */ 
// しかし、形状...<TKey, TValue>でさらに制限できます
/** @return array{someKey: string, ...<int, bool>} */
```

## 呼び出し可能配列

PHPのネイティブな`call_user_func()`やその関連関数がサポートするような、呼び出し可能なものを保持する配列：

```php
<?php

$callable = ['myClass', 'aMethod'];
$callable = [$object, 'aMethod'];
```

## non-empty-array

空であることが許されない配列。
[ジェネリック構文](#generic-arrays)もサポートされています：`non-empty-array<string, int>`。

# 配列型

[前の内容は省略...]

## callable-array

Psalmは`callable-array`型もサポートしています。これは2つの要素を持つ配列で、最初の要素はクラス名または`object`、2番目の要素は文字列（メソッド名）です。

```php
/** @psalm-type CallableArray = callable-array */
/**
 * @param CallableArray $c
 * @psalm-return CallableArray
 */
function foo(array $c): array {
    return $c;
}

class A {
    public function bb(): void {}
}

foo([A::class, 'bb']); // OK
foo(['A', 'bb']); // OK
foo([new A(), 'bb']); // OK
foo(['A', 'cc']); // Psalm error
foo(['B', 'bb']); // Psalm error
```

## class-string

`class-string`は完全修飾クラス名を含む文字列を表します。
`new`や`instanceof`でこの文字列を使用できます。

```php
/** @psalm-param class-string $class */
function instantiator(string $class) {
    $object = new $class(); // works
    return $object;
}

instantiator(\My\ClassName::class); // OK
instantiator("MyClassName"); // Error if MyClassName is not in the global namespace
instantiator("My\\ClassName"); // OK
```

`class-string`は特定のクラスに制限することもできます：

```php
/** @psalm-param class-string<Foo> $classFoo */
function instantiator(string $classFoo) {
    $object = new $classFoo(); // $object is an instance of Foo
    return $object;
}
```

`T`は`object`を拡張するクラスを表します：

```php
/**
 * @template T of object
 * @psalm-param class-string<T> $class
 * @psalm-return T
 */
function instantiator(string $class) {
    $object = new $class();
    return $object;
}
```

## interface-string

`interface-string`はインターフェース名を含む文字列を表します。これは`class-string`と同様に機能しますが、インターフェースに限定されます。

```php
/** @psalm-param interface-string $interface */
function useInterface(string $interface) {
    $object = new class implements $interface {}; // works
    return $object;
}

useInterface(\My\InterfaceName::class); // OK
useInterface("MyInterface"); // Error if MyInterface is not in the global namespace
useInterface("My\\InterfaceName"); // OK
```

## trait-string

`trait-string`はトレイト名を含む文字列を表します。

```php
/** @psalm-param trait-string $trait */
function useTrait(string $trait) {
    $object = new class {
        use $trait; // PHP doesn't actually support this syntax
    };
    return $object;
}

useTrait(\My\TraitName::class); // OK
useTrait("MyTrait"); // Error if MyTrait is not in the global namespace
useTrait("My\\TraitName"); // OK
```

## callable-string

`callable-string`は呼び出し可能な関数名を含む文字列を表します。

```php
/** @psalm-param callable-string $callback */
function call(string $callback) {
    $callback(); // works
}

call('htmlspecialchars'); // OK
call('html_special_chars'); // Error
```

## numeric-string

`numeric-string`は数値を表す文字列を表します。

```php
/** @psalm-param numeric-string $numeric */
function use_numeric(string $numeric) {
    return $numeric + 1; // works
}

use_numeric('42'); // OK
use_numeric('42.1'); // OK
use_numeric('foo'); // Error
```

## class-string-map

`class-string-map`は、キーが任意の型で、値が`class-string`である配列を表します。

```php
/**
 * @psalm-param class-string-map<string, stdClass> $classes
 */
function foo(array $classes): void {}

foo(['a' => stdClass::class, 'b' => Iterator::class]); // Error
foo(['a' => stdClass::class, 'b' => ArrayObject::class]); // OK
```

## リテラル文字列

文字列リテラル型を使用して、特定の文字列値のみを許可することができます。

```php
/** @psalm-param 'foo'|'bar' $param */
function foo(string $param): void {}

foo('foo'); // OK
foo('bar'); // OK
foo('baz'); // Error
```

## リテラル整数

整数リテラル型を使用して、特定の整数値のみを許可することができます。

```php
/** @psalm-param 1|2|3 $param */
function foo(int $param): void {}

foo(1); // OK
foo(2); // OK
foo(4); // Error
```

これらの型を組み合わせて使用することで、PHPコードの静的型チェックをより厳密に行うことができます。Psalmを使用することで、多くの潜在的なバグを事前に発見し、コードの品質を向上させることができます。

# 配列タイプ

PHPでは、`array` 型は3つの異なるデータ構造を表現するためによく使われます：

[List](https://en.wikipedia.org/wiki/List_(abstract_data_type)):

```php
<?php
$a = [1, 2, 3, 4, 5];
```

[Associative array](https://en.wikipedia.org/wiki/Associative_array): 

```php
<?php
$a = [0 => 'hello', 5 => 'goodbye'];
$b = ['a' => 'AA', 'b' => 'BB', 'c' => 'CC'];
```

Makeshift[Structs](https://en.wikipedia.org/wiki/Struct_(C_programming_language))：

```php
<?php
$a = ['name' => 'Psalm', 'type' => 'tool'];
```

PHP は、基本的にこれらの配列をすべて同じように扱います (ただし、最初のケースについては、いくつかの最適化が行われています)。

Psalmの型システムでは、配列を表現するいくつかの方法があります：

## 汎用配列

Psalmは[borrowed from Java](https://en.wikipedia.org/wiki/Generics_in_Java) という構文を使い、キーと値の両方の型を表すことができます：

```php
/** @return array<TKey, TValue> */
```

配列が空でないことを指定するには、特殊な型 `non-empty-array<TKey, TValue>`.

### PHPDoc の構文

PHPDoc[allows you to specify](https://docs.phpdoc.org/latest/guide/references/phpdoc/types.html#arrays) は、一般的な配列が持つ値の型をアノテーションで指定します：

```php
/** @return ValueType[] */
```

Psalm では、このアノテーションは次のようになります。 `@psalm-return array<array-key, ValueType>`.

一般的な配列には、_連想配列_と_リスト_の両方が含まれます。

## リスト

(Psalm 3.6+)

Psalmは、`["red", "yellow", "blue"]` のような、連続した整数インデックスの配列を表す`list` 型をサポートしています。

リストを作成する方法としてよく使われるのが、`$arr[] =` 記法である。

これらの配列は`array_is_list($arr)`(PHP 8.1 以降) に対して true を返し、PHP アプリケーションで使用される配列の大部分を占めます。

`list` 型は `list<SomeType>`ここで`SomeType` は、Psalm がサポートする任意の[union type](union_types.md) です。

-`list` は `array<int, mixed>`- `list<Foo>`のサブタイプである。 `array<int, Foo>`.

リスト型はいくつかの方法でその値を示す：

```php
<?php
/**
 * @param array<int, string> $arr
 */
function takesArray(array $arr) : void {
  if ($arr) {
     // this index may not be set
    echo $arr[0];
  }
}

/**
 * @psalm-param list<string> $arr
 */
function takesList(array $arr) : void {
  if ($arr) {
    // list indexes always start from zero,
    // so a non-empty list will have an element here
    echo $arr[0];
  }
}

takesArray(["hello"]); // this is fine
takesArray([1 => "hello"]); // would trigger bug, without warning

takesList(["hello"]); // this is fine
takesList([1 => "hello"]); // triggers warning in Psalm
```

## 配列の形

Psalmは、キーのオフセットが既知の配列のための特別なフォーマットをサポートしています。

配列

```php
<?php
["hello", "world", "foo" => new stdClass, 28 => false];
```

Psalmはそれを内部的にこうタイプする：

```
array{0: string, 1: string, foo: stdClass, 28: false}
```

このフォーマットで型を指定することもできます。

```php
/** @return array{foo: string, bar: int} */
```

オプションのキーは、末尾に`?` を付けて表すことができます：

```php
/** @return array{optional?: string, bar: int} */
```

PHPに似た "1行 "コメントを使うことができます：

```php
/** @return array { // Array with comments.
 *     // Comments can be placed on their own line. 
 *     foo: string, // An array key description.
 *     bar: array {, // Another array key description.
 *         'foo//bar': string, // Array key with "//" in it's name.
 *     },
 * }
 */
```

ヒント:`InvalidArgument` の問題を避けるために、同じ複雑な配列の形を何度もコピーしてしまう場合は、代わりに[type aliases](utility_types.md#type-aliases) を使ってみてください。

### 配列形状の検証

[Valinor](https://github.com/CuyZ/Valinor) を strict モードで使用すると、Psalm の array shape 構文を使用して、実行時に配列の形状を簡単にアサートできます (isset を使用してキーを手動でアサートする代わりに)：

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
  // Do something…
}
```

[Valinor documentation](https://valinor.cuyz.io/latest/) Valinor は Psalm 構文を完全にサポートし、その他多くの機能を持つ Psalm の実行時アサーションと静的アサーションを提供します！

## リスト形状

Psalm 5から、Psalmはキーのオフセットが既知のリスト配列のための特別なフォーマットもサポートしています。

リスト配列

```php
<?php
["hello", "world", new stdClass, false];
```

Psalmは内部的にこうタイプする：

```
list{string, string, stdClass, false}
```

このフォーマットで型を指定することもできます。

```php
/** @return list{string, int} */
/** @return list{0: string, 1: int} */
```

オプションのキーは、すべての要素にキーを指定し、オプションのキーには末尾に`?` を指定することで表すことができます：

```php
/** @return list{0: string, 1?: int} */
```

リストの形は基本的にnタプルである[from a type theory perspective](https://en.wikipedia.org/wiki/Tuple#Type_theory) 。

## 非シール配列とリスト形状

Psalm v5から、配列シェイプとリストシェイプは、最後の要素に`...` を追加することで、オープンとしてマークすることができます。

ここでは、オプションの配列を受け取る関数`handleOptions` 。この型は、`string` の既知のキーが1つあり、その他に未知の型のキーが多数ある可能性を示しています。

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

`...` の省略記法です。 `...<array-key, mixed>`を省略したもので、他の配列ジェネリック型を使用することで、オープンシェイプに関するより多くの情報を提供することができます。

```php
// This is an open array /** @param array{someKey: string, ...} */  // Which is the same as /** @param array{someKey: string, ...<array-key, mixed>} */  // But it can be further locked down with a shape ...<TKey, TValue> /** @return array{someKey: string, ...<int, bool>} */
```

## 呼び出し可能な配列

callable を保持する配列は、PHP ネイティブの`call_user_func()` やその仲間たちがサポートしています：

```php
<?php

$callable = ['myClass', 'aMethod']; $callable = [$object, 'aMethod'];
```

## 空でない配列

空であってはならない配列。
[Generic syntax](#generic-arrays) もサポートしています： `non-empty-array<string, int>`.

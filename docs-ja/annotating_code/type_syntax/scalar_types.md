# スカラー型

## スカラー

`int`、`bool`、`float`、`string` はスカラー型の例です。スカラー型はPHPのスカラー値を表します。これらの型はPHP 7でも有効な型です。
`scalar` 型はすべてのスカラー型のスーパータイプです。

## int-range

整数範囲は、ジェネリック構文を使用して指定された範囲内の整数を示します：`int<x, y>`  
`x` と `y` は整数である必要があります。  
`x` は `min` を使用して PHP_INT_MIN を示すこともでき、`y` は `max` を使用して PHP_INT_MAX を示すこともできます。

例：

* `int<-1, 3>`
* `int<min, 0>`
* `int<1, max>` （`positive-int` と同等）
* `int<0, max>` （`non-negative-int` と同等）
* `int<min, -1>` （`negative-int` と同等）
* `int<min, 0>` （`non-positive-int` と同等）
* `int<min, max>` （`int` と同等）

## int-mask&lt;1, 2, 4&gt;

パラメータのビットマスク組み合わせの結果である型を表します。  
`int-mask<1, 2, 4>` は `0|1|2|3|4|5|6|7` に対応します。

## int-mask-of&lt;MyClass::CLASS_CONSTANT_*&gt;

パラメータのビットマスク組み合わせの結果である型を表します。  
これは [`int-mask`](#int-mask1-2-4) と同じ概念ですが、この型はコード内の定数への参照とともに使用されます：`int-mask-of<MyClass::CLASS_CONSTANT_*>` は、値が1、2、4の `CLASS_CONSTANT_{A,B,C}` という3つの定数がある場合、`0|1|2|3|4|5|6|7` に対応します。

## array-key

`array-key` は `int` と `string` のスーパータイプです（ただし、ユニオンではありません）。

## numeric

`numeric` は `int` または `float` と [`numeric-string`](#numeric-string) のスーパータイプです。

## class-string, interface-string

Psalmは `MyClass::class` 定数のための特別なメタ型 `class-string` をサポートしており、これは `string` が使用できるどこでも使用できます。

例えば、`string` パラメータ `$class_name` を持つ関数があるとき、アノテーション `@param class-string $class_name` を使用して、その位置に常に `::class` 定数が渡されることをPsalmに確認させることができます：

```php
<?php
class A {}

/**
 * @param class-string $s
 */
function takesClassName(string $s) : void {}
```

`takesClassName("A");` は `TypeCoercion` の問題を引き起こしますが、`takesClassName(A::class)` は問題ありません。

また、`class-string` をオブジェクト名でパラメータ化することもできます。例：[`class-string<Foo>`](value_types.md#regular-class-constants)。これは、一致する型が `Foo` のクラス文字列またはその子孫のいずれかでなければならないことをPsalmに伝えます。

## trait-string

Psalmは存在するトレイトを示す `trait-string` アノテーションもサポートしています。

## enum-string

Psalmは存在する列挙型を示す `enum-string` アノテーションもサポートしています。

## callable-string

`callable-string` は `is_callable` チェックを通過した文字列値を示します。

## numeric-string

`numeric-string` は `is_numeric` チェックを通過した文字列値を示します。

## literal-string

`literal-string` はアプリケーション内の文字列のみで構成される文字列値を示します。

例：

- `"hello " . "world"`
- `"hello " . Person::DEFAULT_NAME`
- `implode(', ', ["one", "two"])`
- `implode(', ', [1, 2, 3])`
- `"hello " . <another literal-string>`

このタイプチェックを通過しない文字列：

- `file_get_contents("foo.txt")`
- `$_GET["foo"]`
- `"hello " . $_GET["foo"]`

## literal-int

`literal-int` はアプリケーション内のリテラル整数のみで構成される整数値を示します。

例：

- `12`
- `12+42`

このタイプチェックを通過しない整数：

- `(int) file_get_contents("foo.txt")`
- `(int) $_GET["foo"]`
- `((int)$_GET["foo"]) + 2`

## lowercase-string, non-empty-string, non-empty-lowercase-string

空でない文字列、小文字の文字列、または両方を同時に表します。

ここでの `empty` は空文字列 `''` を除くすべての文字列として定義されています。別の型 `non-falsy-string` は事実上 `non-empty-string` のサブタイプであり、文字列値 `'0'` も除外します。

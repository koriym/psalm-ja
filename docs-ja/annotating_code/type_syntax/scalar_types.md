# スカラー型

## スカラー

`int` `bool`,`float`,`string` はスカラー型の例です。スカラー型は、PHPにおけるスカラー値を表します。これらの型は PHP 7 でも有効な型です。
型`scalar` はすべてのスカラー型の上位型です。

## int-range

整数範囲は、一般的な構文で指定された範囲内の整数を示します： `int<x, y>`.  
`x` と`y` は整数でなければならない。  
`x` には`min` を指定して PHP_INT_MIN を、`y` には`max` を指定して PHP_INT_MAX を指定することもできます。

例

* `int<-1, 3>`
* `int<min, 0>`
* `int<1, max>`(`positive-int` と同等)
* `int<0, max>`(`non-negative-int` と同等)
* `int<min, -1>`(`negative-int` と同等)
* `int<min, 0>`(`non-positive-int` と同等)
* `int<min, max>`(`int` と同等)

## int-mask&lt;1, 2, 4&gt; (イントマスク&lt;1, 2, 4&gt;)

パラメータをビットマスクで組み合わせた結果の型を表す。  
`int-mask<1, 2, 4>``0|1|2|3|4|5|6|7` に対応。  

## int-mask-of&lt;MyClass::CLASS_CONSTANT_*&gt; 型。

パラメータをビットマスクで組み合わせた結果の型を表します。   これは[`int-mask`](#int-mask1-2-4) と同じ概念ですが、この型はコード内で定数への参照と共に使用されます： `int-mask-of<MyClass::CLASS_CONSTANT_*>`1、2、4の値を持つ`CLASS_CONSTANT_{A,B,C}` という3つの定数がある場合、`0|1|2|3|4|5|6|7` に対応します。  

## 配列キー

`array-key` は、`int` と`string` のスーパータイプ（ユニオンではない）である。

## 数値

`numeric` は`int` または`float` と[`numeric-string`](#numeric-string) の上位型である。

## クラス文字列、インターフェイス文字列

Psalmは、`MyClass::class` 定数のための特別なメタ型、`class-string` をサポートしており、`string` が使えるところならどこでも使うことができます。

例えば、`string` パラメータ`$class_name` を持つ関数がある場合、`@param class-string $class_name` というアノテーションを使うことで、その関数が常に`::class` の定数で呼び出されるようにPsalmに指示することができます：

```php
<?php
class A {}

/**
 * @param class-string $s
 */
function takesClassName(string $s) : void {}
```

`takesClassName("A");` を指定すると、`TypeCoercion` の問題が発生しますが、`takesClassName(A::class)` は問題ありません。

また、`class-string` をオブジェクト名でパラメータ化することもできます。 [`class-string<Foo>`](value_types.md#regular-class-constants).これはPsalmに、マッチする型は`Foo` のクラス文字列か、その子孫のいずれかでなければならないことを伝えます。

## trait-string

Psalmは、存在する特徴を示す`trait-string` のアノテーションもサポートしています。

## 列挙型文字列

Psalm は、存在する列挙型を表す`enum-string` アノテーションもサポートしています。

## callable-string

`callable-string` は、`is_callable` チェックを通過した文字列値を表す。

## 数値文字列

`numeric-string` は、`is_numeric` のチェックを通過した文字列値を表す。

## リテラル文字列

`literal-string` は、アプリケーション内で文字列のみで構成される文字列値を表します。

例

-`"hello " . "world"` -`"hello " . Person::DEFAULT_NAME` -`implode(', ', ["one", "two"])` -`implode(', ', [1, 2, 3])` - 。 `"hello " . <another literal-string>`

この型チェックを通過しない文字列：

-`file_get_contents("foo.txt")` -`$_GET["foo"]` `"hello " . $_GET["foo"]`

## リテラル int

`literal-int` は、アプリケーション内でリテラル整数のみで構成されるint値を表します。

例

-`12`
-`12+42`

この型チェックをパスしない整数：

-`(int) file_get_contents("foo.txt")`
-`(int) $_GET["foo"]`
-`((int)$_GET["foo"]) + 2`

## 小文字列、空でない文字列、空でない小文字列

空でない文字列、小文字、またはその両方。

`empty` ここでは、空文字列`''` 以外のすべての文字列として定義される。もう1つの型`non-falsy-string` は事実上`non-empty-string` のサブタイプであり、文字列値`'0'` も除外する。

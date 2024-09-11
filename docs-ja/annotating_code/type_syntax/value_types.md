# 値の種類

Psalmでは、値の型を指定することもできます。

## ヌル

これは`null` の値であり、世界の破壊者である。控えめに使ってください。Psalmは、`?Foo` を`null|Foo` と書くことをサポートしています。

## 真、偽

`true` や`false` も PHPDoc と互換性があります。

## "some_string", 4, 3.14

Psalm では、リテラル値を型に指定することもできます。`@return "good"|"bad"`

## 通常のクラス定数

Psalmでは、`@return Foo::GOOD|Foo::BAD` のように、型にクラス定数を含めることができます。また、明示的なクラス文字列を指定することもできます。`Foo::class|Bar::class`

あるパラメータが、指定したクラスであるか、あるいはそのクラスを拡張したクラス文字列のみを受け取るように指定したい場合は、次のアノテーションを使用します。 `@param class-string<Foo> $foo_class`.そのクラス文字列のみをパラメータに指定したい場合は、`Foo::class` というアノテーションを使用します：

```php
<?php
class A {}
class AChild extends A {}
class B {}
class BChild extends B {}

/**
 * @param class-string<A>|class-string<B> $s
 */
function foo(string $s) : void {}

/**
 * @param A::class|B::class $s
 */
function bar(string $s) : void {}

foo(A::class); // works
foo(AChild::class); // works
foo(B::class); // works
foo(BChild::class); // works
bar(A::class); // works
bar(AChild::class); // fails
bar(B::class); // works
bar(BChild::class); // fails
```

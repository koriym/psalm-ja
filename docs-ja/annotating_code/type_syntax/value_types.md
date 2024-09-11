# 値型

Psalmでは、型で値を指定することもできます。

## null

これは `null` 値で、世界の破壊者です。慎重に使用してください。Psalmでは、`?Foo` と書くことで `null|Foo` を意味することができます。

## true, false

`true` と `false` の使用もPHPDocと互換性があります。

## "some_string", 4, 3.14

Psalmでは、型にリテラル値を指定することもできます。例：`@return "good"|"bad"`

## 通常のクラス定数

Psalmでは、型にクラス定数を含めることができます。例：`@return Foo::GOOD|Foo::BAD`。また、明示的なクラス文字列を指定することもできます。例：`Foo::class|Bar::class`

特定のクラスの、またはそのクラスを拡張したクラス文字列のみをパラメータとして受け入れるように指定したい場合は、`@param class-string<Foo> $foo_class` というアノテーションを使用できます。そのクラス文字列だけを受け入れるようにしたい場合は、`Foo::class` というアノテーションを使用できます：

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

foo(A::class); // 動作します
foo(AChild::class); // 動作します
foo(B::class); // 動作します
foo(BChild::class); // 動作します

bar(A::class); // 動作します
bar(AChild::class); // 失敗します
bar(B::class); // 動作します
bar(BChild::class); // 失敗します
```

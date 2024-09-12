# MethodSignatureMustProvideReturnType
PHP 8.1以降、[ほとんどの非finalな内部メソッドは、オーバーライドするメソッドが互換性のある戻り値の型を宣言することを要求するようになりました。そうでない場合、継承の検証中に非推奨の通知が発行されます](https://www.php.net/manual/en/migration81.incompatible.php#migration81.incompatible.core.type-compatibility-internal)。この問題は、ネイティブメソッドをオーバーライドするメソッドが戻り値の型なしで定義されている場合に発生します。**PHP 7のサポートを維持するために戻り値の型を宣言できない場合のみ**、`#[ReturnTypeWillChange]`属性を追加してPHPの非推奨通知とPsalmの問題を抑制することができます。

```php
<?php
class A implements JsonSerializable {
    public function jsonSerialize() {
        return ['type' => 'A'];
    }
}
```

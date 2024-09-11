# MethodSignatureMustProvideReturnType

PHP 8.1 以降では、[most non-final internal methods now require overriding methods to declare a compatible return type, otherwise a deprecated notice is emitted during inheritance validation](https://www.php.net/manual/en/migration81.incompatible.php#migration81.incompatible.core.type-compatibility-internal). 

この問題は、ネイティブメソッドをオーバーライドするメソッドが戻り値の型を指定せずに定義された場合に発生します。  

**PHP7のサポートを維持するために戻り値の型を宣言できない場合のみ**、`#[ReturnTypeWillChange]` 属性を追加することで、PHPの非推奨通知とPsalm問題を無効にすることができます。  

```php
<?php
class A implements JsonSerializable {
    public function jsonSerialize() {
        return ['type' => 'A'];
    }
}
```

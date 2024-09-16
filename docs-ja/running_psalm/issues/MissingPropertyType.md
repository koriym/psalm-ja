# MissingPropertyType
クラスでプロパティが型なしで定義されている場合に発生します。

```php
<?php
class A {
    public $foo = 5;
}
```

この機能はPHPバージョン7.4以降で利用可能です。
詳細については以下を参照してください：
* PHPマニュアルの[プロパティ](https://www.php.net/manual/en/language.oop5.properties.php)。
* PHP RFC: [Typed Properties 2.0](https://wiki.php.net/rfc/typed_properties_v2)

# MissingPropertyType

型がないクラスにプロパティが定義された場合に発行されます。

```php
<?php

class A {
    public $foo = 5;
}
```

この機能は PHP バージョン 7.4 以降で利用可能です。

詳細は* PHP マニュアルの[Properties](https://www.php.net/manual/en/language.oop5.properties.php) を参照してください。* PHP RFC：[Typed Properties 2.0](https://wiki.php.net/rfc/typed_properties_v2)

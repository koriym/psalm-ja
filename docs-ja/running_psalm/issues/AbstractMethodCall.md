# AbstractMethodCall
抽象的な静的メソッドを直接呼び出そうとした場合に発生します。

```php
<?php
abstract class Base {
    abstract static function bar() : void;
}

Base::bar();
```

## なぜこれが問題なのか
PHPでは許可されていません。

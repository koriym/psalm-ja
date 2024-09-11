# AbstractMethodCall

抽象スタティックメソッドを直接呼び出そうとしたときに発行されます。

```php
<?php

abstract class Base {
    abstract static function bar() : void;
}

Base::bar();
```

## なぜこれが悪いのか

PHPでは許可されていません。

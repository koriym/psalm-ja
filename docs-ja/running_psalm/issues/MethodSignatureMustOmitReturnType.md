# MethodSignatureMustOmitReturnType

`__clone` 、`__construct` 、`__destruct` のメソッドがリターン型で定義されている場合に発行されます。

```php
<?php

class A {
    public function __clone() : void {}
}
```

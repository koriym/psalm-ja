# 可能なプロパティ割り当て値

NULLでない値しかとらないプロパティにNULLの可能性のある値を代入しようとしたときに発行されます。

```php
<?php

class A {
    /** @var string */
    public $foo = "bar";
}

function assignToA(?string $s) {
    $a = new A();
    $a->foo = $s;
}
```

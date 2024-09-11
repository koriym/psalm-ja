# 可能性があります。

偽でない値のみを取るプロパティに、偽である可能性のある値を代入しようとしたときに発行されます。

```php
<?php

class A {
    /** @var int */
    public $foo = 0;
}

function assignToA(string $s) {
    $a = new A();
    $a->foo = strpos($s, "haystack");
}
```

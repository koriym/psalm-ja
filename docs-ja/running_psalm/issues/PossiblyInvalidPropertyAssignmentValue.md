# 不正なプロパティ割り当て値の可能性

型付きプロパティに無効な値を代入しようとしたときに発行されます。

```php
<?php

class A {
    /** @var int[] */
    public $bb = [];
}

class B {
    /** @var string[] */
    public $bb;
}

$c = rand(0, 1) ? new A : new B;
$c->bb = ["hello", "world"];
```

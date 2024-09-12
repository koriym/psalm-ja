# PossiblyInvalidPropertyAssignmentValue
可能性のある無効な値を型付きプロパティに割り当てようとした場合に発生します。

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

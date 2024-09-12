# PossiblyFalsePropertyAssignmentValue
falseの可能性がある値を、非falseの値のみを受け入れるプロパティに割り当てようとした場合に発生します。

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

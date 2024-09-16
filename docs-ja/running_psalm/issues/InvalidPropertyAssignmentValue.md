# InvalidPropertyAssignmentValue
その型を含むことができないプロパティに値を割り当てようとした場合に発生します。

```php
<?php
class A {
    /** @var string|null */
    public $foo;
}

$a = new A();
$a->foo = new stdClass();
```

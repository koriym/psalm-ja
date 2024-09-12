# UndefinedMagicPropertyAssignment
定義されていないマジックプロパティを持つオブジェクトにプロパティを割り当てようとした場合に発生します。

```php
<?php
/** 
 * @property string $bar 
 */
class A {
    /** @param mixed $value */
    public function __set(string $name, $value) {}
}

$a = new A();
$a->foo = "bar";
```

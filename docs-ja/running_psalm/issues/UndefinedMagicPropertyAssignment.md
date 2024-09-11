# UndefinedMagicPropertyAssignment（未定義マジックプロパティ割り当て

そのマジック・プロパティが定義されていないオブジェクトにプロパティを割り当てる際に発せられる

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

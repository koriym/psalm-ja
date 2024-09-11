# InvalidPropertyAssignmentValue（無効なプロパティ割り当て値

その型を含むことができないプロパティに値を割り当てようとしたときに発行されます。

```php
<?php

class A {
    /** @var string|null */
    public $foo;
}
$a = new A();
$a->foo = new stdClass();
```

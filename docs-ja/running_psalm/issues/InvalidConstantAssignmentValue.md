# InvalidConstantAssignmentValue（無効な定数割り当て値

その型を含むことができないクラス定数に値を代入しようとしたときに発行されます。

```php
<?php

class Foo {
    /** @var int */
    public const BAR = "bar";
}
```

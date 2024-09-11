# MissingClosureReturnType

クロージャに戻り値の型がない場合に発行されます。

```php
<?php

$a = function() {
    return "foo";
};
```

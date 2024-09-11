# InvalidFalsableReturnType

関数がnull可能な値を返すことができるが、指定された戻り値の型がそうでない場合に発行されます。

```php
<?php

function foo() : string {
    if (rand(0, 1)) {
        return "foo";
    }

    return false;
}
```

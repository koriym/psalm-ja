# InvalidNullableReturnType

関数が NULL 値を返すことができるが、指定された戻り値の型がそうでない場合に発行されます。

```php
<?php

function foo() : string {
    if (rand(0, 1)) {
        return "foo";
    }

    return null;
}
```

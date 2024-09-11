# NullableReturnStatement

returnステートメントにNULL値が含まれているが、関数の戻り値の型がNULL可能でない場合に発行されます。

```php
<?php

function foo() : string {
    if (rand(0, 1)) {
        return "foo";
    }

    return null; // emitted here
}
```

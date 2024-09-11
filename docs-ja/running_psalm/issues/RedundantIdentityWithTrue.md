# RedundantIdentityWithTrue

既知のブール値を true と比較し、`strictBinaryOperands` フラグが true に設定されている場合に発行されます。

```php
<?php

function returnsABool(): bool {
    return rand(1, 2) === 1;
}

if (returnsABool() === true) {
    echo "hi!";
}

if (returnsABool() !== false) {
    echo "hi!";
}
```

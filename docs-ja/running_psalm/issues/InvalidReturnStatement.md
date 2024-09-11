# 無効なリターンステートメント

関数のリターンステートメントが不正な場合に返されます。

```php
<?php

function foo() : string {
    return 5; // emitted here
}
```

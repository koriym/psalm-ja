# UncaughtThrowInGlobalScope

グローバルスコープで例外が捕捉されなかった場合に発生する

```php
<?php

/**
 * @throws \Exception
 */
function foo() : int {
    return random_int(0, 1);
}
foo();
```

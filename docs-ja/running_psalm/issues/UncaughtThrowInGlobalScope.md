# UncaughtThrowInGlobalScope
グローバルスコープで可能な例外がキャッチされていない場合に発生します。

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

# 無効な引数の可能性

以下の場合に発行されます。

```php
<?php

/** @return int|stdClass */
function foo() {
    return rand(0, 1) ? 5 : new stdClass;
}
function bar(int $i) : void {}
bar(foo());
```

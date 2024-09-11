# パラドキシカル・コンディション

プログラムのロジックでパラドックスが発生し、それが`RedundantCondition`

```php
<?php

function foo(string $input) : string {
    return $input === "a" ? "bar" : ($input === "a" ? "foo" : "b");
}
```

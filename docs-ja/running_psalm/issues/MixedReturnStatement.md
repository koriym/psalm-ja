# ♪ MixedReturnStatement

Psalmが指定されたreturn文の型を判別できない場合に発せられる。

```php
<?php

function foo() : int {
    return $GLOBALS['foo']; // emitted here
}
```

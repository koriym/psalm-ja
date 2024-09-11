# PossiblyNullIterator

NULLである可能性のある値を反復処理しようとしたときに返されます。

```php
<?php

function foo(?array $arr) : void {
    foreach ($arr as $a) {}
}
```

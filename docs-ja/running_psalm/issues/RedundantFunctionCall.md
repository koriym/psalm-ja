# RedundantFunctionCall
関数呼び出し（`array_values`、`strtolower`、`ksort`など）が冗長な場合に発生します。

```php
<?php
$a = ['already', 'a', 'list'];
$redundant = array_values($a);
$alreadyLower = strtolower($redundant[0]);
```

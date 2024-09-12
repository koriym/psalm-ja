# PossiblyInvalidOperand
可能性のある無効な値を操作の一部として使用した場合（例：`+`、`.`、`^`など）に発生します。

```php
<?php
function foo() : void {
    $b = rand(0, 1) ? [] : 4;
    echo $b + 5;
}
```

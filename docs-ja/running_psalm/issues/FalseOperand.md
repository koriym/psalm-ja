# FalseOperand
`false`を操作の一部として使用した場合（例：`+`、`.`、`^`など）に発生します。

```php
<?php
echo 5 . false;
```

## なぜこれが問題なのか
`false`はこれらの操作では意味をなしません。

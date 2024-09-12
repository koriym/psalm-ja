# MixedFunctionCall
Psalmが型を推論できない値に対して関数を呼び出そうとした場合に発生します。

```php
<?php
/** @var mixed */
$a = $GLOBALS['foo'];
$a();
```

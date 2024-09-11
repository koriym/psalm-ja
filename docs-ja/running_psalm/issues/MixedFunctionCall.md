# ♪ MixedFunctionCall

Psalmが型を推測できない値に対して関数を呼び出したときに発せられる。

```php
<?php

/** @var mixed */
$a = $GLOBALS['foo'];
$a();
```

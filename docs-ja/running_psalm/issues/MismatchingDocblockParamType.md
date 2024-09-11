# ♪ MismatchingDocblockParamType

関数のdocblockの`@param` エントリが、paramのtypehintと一致しない場合に発行されます。

```php
<?php

/**
 * @param int $b
 */
function foo(string $b) : void {}
```

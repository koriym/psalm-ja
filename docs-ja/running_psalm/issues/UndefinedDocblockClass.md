# UndefinedDocblockClass

docblockから存在しないクラスを参照するときに発行されます。

```php
<?php

/**
 * @param DoesNotExist $a
 */
function foo($a) : void {}
```

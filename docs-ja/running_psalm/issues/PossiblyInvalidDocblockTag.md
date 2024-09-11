# 無効なDocblockタグの可能性

Psalmがdocblockタグの取り違えを検出した時に発行されます。例えば、`@var` がメソッドで使用されています(`@param` が予想されます)。

```php
<?php

/** @var int $param */
function foo($param): void {}
```

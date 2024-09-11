# MixedStringOffsetAssignment（混合文字列オフセット割り当て

Psalmが型を推測できない値を使って文字列に値を代入するときに発行されます。

```php
<?php

"hello"[0] = $GLOBALS['foo'];
```

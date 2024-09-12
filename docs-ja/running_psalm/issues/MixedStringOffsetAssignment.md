# MixedStringOffsetAssignment
Psalmが型を推論できない値を使用して文字列にオフセットを割り当てようとした場合に発生します。

```php
<?php
"hello"[0] = $GLOBALS['foo'];
```

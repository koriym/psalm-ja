# MixedArrayOffset
Psalmがオフセットの型を判断できない場合に配列オフセットにアクセスしようとした場合に発生します。

```php
<?php
echo [1, 2, 3][$GLOBALS['foo']];
```

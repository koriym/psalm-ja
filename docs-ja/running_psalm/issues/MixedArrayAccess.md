# MixedArrayAccess
Psalmが型を判断できない値の配列オフセットにアクセスしようとした場合に発生します。

```php
<?php
echo $GLOBALS['foo'][0];
```

# PossiblyInvalidArrayAccess
配列ではない可能性のある値の配列オフセットにアクセスしようとした場合に発生します。

```php
<?php
$arr = rand(0, 1) ? 5 : [4, 3, 2, 1];
echo $arr[0];
```

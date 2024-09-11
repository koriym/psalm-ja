# PossiblyInvalidArrayAccess（無効な配列アクセスの可能性

配列でない可能性のある値の配列オフセットにアクセスしようとしたときに発行されます。

```php
<?php

$arr = rand(0, 1) ? 5 : [4, 3, 2, 1];
echo $arr[0];
```

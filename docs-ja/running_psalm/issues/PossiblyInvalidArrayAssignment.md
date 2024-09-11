# PossiblyInvalidArrayAssignment（配列の割り当てが無効な可能性

配列でない可能性のある値に配列のオフセットを代入しようとしたときに発行されます。

```php
<?php

$arr = rand(0, 1) ? 5 : [4, 3, 2, 1];
$arr[0] = "hello";
```

# 可能性のある偽イテレーター

である可能性のある値を反復処理しようとしたときに返される。`false`

```php
<?php

$arr = rand(0, 1) ? [1, 2, 3] : false;
foreach ($arr as $a) {}
```

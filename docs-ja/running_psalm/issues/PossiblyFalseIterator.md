# PossiblyFalseIterator
`false`の可能性のある値を反復しようとした場合に発生します。

```php
<?php
$arr = rand(0, 1) ? [1, 2, 3] : false;
foreach ($arr as $a) {}
```

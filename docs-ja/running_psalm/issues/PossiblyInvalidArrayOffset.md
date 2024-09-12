# PossiblyInvalidArrayOffset
アクセスしようとしている値に配列オフセットが適用できない可能性がある場合に発生します。

```php
<?php
$arr = rand(0, 1) ? ["a" => 5] : "hello";
echo $arr[0];
```

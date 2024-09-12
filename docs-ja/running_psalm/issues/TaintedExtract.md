# TaintedExtract
ユーザー制御の配列が`extract`呼び出しに渡される可能性がある場合に発生します。

```php
<?php
$array = $_GET;
extract($array);
```

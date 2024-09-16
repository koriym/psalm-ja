# PossiblyNullArrayOffset
nullの可能性のあるオフセットを使用して配列の値にアクセスしようとした場合に発生します。

```php
<?php
function foo(?int $a) : void {
    echo [1, 2, 3, 4][$a];
}
```

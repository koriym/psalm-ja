# PossiblyNullArrayAccess
nullの可能性のある値の配列オフセットにアクセスしようとした場合に発生します。

```php
<?php
function foo(?array $a) : void {
    echo $a[0];
}
```

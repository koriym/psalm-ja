# PossiblyUndefinedIntArrayOffset

コンフィグフラグ`ensureArrayIntOffsetsExist` が`true` に設定され、整数キーのオフセットが存在するかどうかチェックされない場合に発行されます。

```php
<?php

/**
 * @param array<int, string> $arr
 */
function foo(array $arr) : void {
    echo $arr[0];
}
```

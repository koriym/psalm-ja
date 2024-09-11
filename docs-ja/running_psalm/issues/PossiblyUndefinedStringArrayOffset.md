# 未定義文字列配列オフセットの可能性

コンフィグフラグ`ensureArrayStringOffsetsExist` が`true` に設定され、整数キーのオフセットが存在するかどうかチェックされない場合に発行されます。

```php
<?php

/**
 * @param array<string, string> $arr
 */
function foo(array $arr) : void {
    echo $arr["hello"];
}
```

# PossiblyUndefinedStringArrayOffset
設定フラグ`ensureArrayStringOffsetsExist`が`true`に設定されており、文字列キーのオフセットの存在がチェックされていない場合に発生します。

```php
<?php
/** 
 * @param array<string, string> $arr 
 */
function foo(array $arr) : void {
    echo $arr["hello"];
}
```

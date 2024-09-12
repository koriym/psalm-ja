# RedundantFunctionCallGivenDocblockType
1つ以上のdocblockで提供された情報を考慮すると関数呼び出しが冗長な場合に発生します。
これは望ましい場合があります（例：ユーザー入力のチェック時）ので、非docblockタイプにのみ適用されるRedundantFunctionCallとは異なります。

```php
<?php
/**
 * @param array{0: lowercase-string, 1: non-empty-list<lowercase-string>} $s
 *
 * @return lowercase-string
 */
function foo($s): string {
    $redundantList = array_values($s);
    $redundantSubList = array_values($s[1]);
    $redundantLowercase = strtolower($redundantSubList[0]);
    return $redundantLowercase;
}
```

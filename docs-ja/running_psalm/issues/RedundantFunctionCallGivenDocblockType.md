冗長ファンクション・コール・ギブン・ドックブロック・タイプ # RedundantFunctionCallGivenDocblockType

1つ以上のdocblockで提供された情報が冗長な関数呼び出しである場合に発行されます。

これは(ユーザ入力をチェックする時などに)必要かもしれませんので、docblock型でない場合にのみ適用されるRedundantFunctionCallとは区別されます。

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

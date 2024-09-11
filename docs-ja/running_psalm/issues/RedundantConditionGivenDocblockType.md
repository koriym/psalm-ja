# RedundantConditionGivenDocblockType（リダンダントコンディションギブンドックブロックタイプ

1つ以上のdocblockで提供された情報が冗長な条件である場合に発行されます。

これは(ユーザ入力をチェックする場合などに)必要かもしれませんので、docblock型以外にのみ適用されるRedundantConditionとは区別されます。

```php
<?php

/**
 * @param string $s
 *
 * @return void
 */
function foo($s) {
    if (is_string($s)) {};
}
```

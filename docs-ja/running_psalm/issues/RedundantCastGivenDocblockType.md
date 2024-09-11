# RedundantCastGivenDocblockType（リダンダントキャストギブンドックブロックタイプ

(文字列、int、floatなどの)キャストがdocblockが提供する型から見て冗長な場合に発行されます。

```php
<?php
/**
 * @param  string $s
 */
function foo($s) : string {
    return (string) $s;
}
```

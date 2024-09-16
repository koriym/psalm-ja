# RedundantCastGivenDocblockType
docblockで提供された型を考慮すると、キャスト（string、int、floatなど）が冗長である場合に発生します。

```php
<?php
/**
 * @param  string $s
 */
function foo($s) : string {
    return (string) $s;
}
```

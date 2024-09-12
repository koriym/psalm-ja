# RedundantCast
キャスト（string、int、floatなど）が冗長な場合に発生します。

```php
<?php
function foo(string $s) : string {
    return (string) $s;
}
```

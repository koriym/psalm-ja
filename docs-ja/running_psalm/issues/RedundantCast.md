# リダンダントキャスト

キャスト(文字列、int、floatなど)が冗長な場合に発行される

```php
<?php
function foo(string $s) : string {
    return (string) $s;
}
```

# MissingClosureReturnType
クロージャに戻り値の型が欠けている場合に発生します。

```php
<?php
$a = function() {
    return "foo";
};
```

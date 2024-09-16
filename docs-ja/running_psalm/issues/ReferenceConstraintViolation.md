# ReferenceConstraintViolation
参照渡し変数の型を変更しようとした場合に発生します。

```php
<?php
function foo(string &$a) {
    $a = 5;
}
```

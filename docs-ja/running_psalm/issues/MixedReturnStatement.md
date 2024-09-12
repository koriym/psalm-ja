# MixedReturnStatement
Psalmが特定のreturn文の型を判断できない場合に発生します。

```php
<?php
function foo() : int {
    return $GLOBALS['foo']; // ここで発生
}
```

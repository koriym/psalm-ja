# MixedPropertyAssignment
Psalmが型を推論できない値をプロパティに割り当てようとした場合に発生します。

```php
<?php
/**
 * @param mixed $a
 */
function foo($a) : void {
    $a->foo = "bar";
}
```

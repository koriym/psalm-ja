# RedundantCondition
以前のアサーションを考慮すると条件が冗長な場合に発生します。

```php
<?php
class A {}
function foo(A $a) : ?A {
    if ($a) return $a;
    return null;
}
```

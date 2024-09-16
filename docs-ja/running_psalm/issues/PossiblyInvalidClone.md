# PossiblyInvalidClone
クローン可能ではない可能性のある値をクローンしようとした場合に発生します。

```php
<?php
class A {}

/**
 * @param A|string $a
 */
function foo($a) {
    return clone $a;
}
```

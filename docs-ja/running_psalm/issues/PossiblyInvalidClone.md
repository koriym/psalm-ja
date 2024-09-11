# PossiblyInvalidClone

クローンできない可能性のある値をクローンしようとしたときに発せられる

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

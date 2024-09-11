# 不純な関数呼び出し

純粋とマークされた関数やメソッドから不純な関数を呼び出すときに発せられる。

```php
<?php

function impure(array $a) : array {
    /** @var int */
    static $i = 0;

    ++$i;

    $a[$i] = 1;

    return $a;
}

/** @psalm-pure */
function filterOdd(array $a) : void {
    impure($a);
}
```

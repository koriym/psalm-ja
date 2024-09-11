# MixedArrayTypeCoercion

期待されるオフセットよりも小さいオフセットで配列にアクセスしようとしたときに発せられる。

```php
<?php

/**
 * @param array<array-key, int> $a
 * @param array<int, string> $b
 */
function foo(array $a, array $b) : void {
    foreach ($a as $j => $k) {
        echo $b[$j];
    }
}
```

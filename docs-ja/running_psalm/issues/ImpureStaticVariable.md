# 不純な静的変数

純粋とマークされた関数またはメソッドから静的変数を使用しようとしたときに発せられる。

```php
<?php

/** @psalm-pure */
function addCumulative(int $left) : int {
    /** @var int */
    static $i = 0;
    $i += $left;
    return $left;
}
```

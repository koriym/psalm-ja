#MissingThrowsDocblock

`checkForThrowsDocblock` 設定オプションが有効な場合に有効。

関数が例外をスローし(または処理に失敗し)、`@throws` アノテーションを持たない場合に発行されます。

```php
<?php

function foo(int $x, int $y) : int {
    if ($y === 0) {
        throw new \InvalidArgumentException('Cannot divide by zero');
    }

    return intdiv($x, $y);
}
```

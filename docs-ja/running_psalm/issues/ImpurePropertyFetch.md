# 不純物プロパティフェッチ

純粋とマークされた関数またはメソッド内でプロパティ値をフェッチするときに発行されます。

```php
<?php

class A {
    public int $a = 5;
}

/** @psalm-pure */
function foo(int $i, A $a) : int {
    return $i + $a->a;
}
```

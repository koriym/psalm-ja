# 不純なプロパティの割り当て

純粋であるとマークされた関数またはメソッドからプロパティ値を更新するときに発行されます。

```php
<?php

class A {
    public int $a = 5;
}

/** @psalm-pure */
function foo(int $i, A $a) : int {
    $a->a = $i;

    return $i;
}
```

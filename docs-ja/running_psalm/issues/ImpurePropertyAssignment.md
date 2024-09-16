# ImpurePropertyAssignment
純粋（pure）とマークされた関数やメソッドからプロパティ値を更新しようとした場合に発生します。

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

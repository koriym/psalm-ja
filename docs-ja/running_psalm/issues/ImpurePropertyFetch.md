# ImpurePropertyFetch
純粋（pure）とマークされた関数やメソッド内でプロパティ値を取得しようとした場合に発生します。

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

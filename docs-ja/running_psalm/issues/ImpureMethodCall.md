# 不純メソッドコール

純粋とマークされた関数やメソッドから不純なメソッドを呼び出すときに発せられる。

```php
<?php

class A {
    public int $a = 5;

    public function foo() : void {
        $this->a++;
    }
}

/** @psalm-pure */
function filterOdd(int $i, A $a) : ?int {
    $a->foo();

    if ($i % 2 === 0 || $a->a === 2) {
        return $i;
    }

    return null;
}
```

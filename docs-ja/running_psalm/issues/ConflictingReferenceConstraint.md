#矛盾する参照制約

ifの2つの異なる分岐でby-ref変数が異なる型に設定されたときに発せられる。

```php
<?php

 class A {
    /** @var int */
    private $foo;

    public function __construct(int &$foo) {
        $this->foo = &$foo;
    }
}

class B {
    /** @var string */
    private $bar;

    public function __construct(string &$bar) {
        $this->bar = &$bar;
    }
}

if (rand(0, 1)) {
    $v = 5;
    $c = (new A($v)); // $v is constrained to an int
} else {
    $v = "hello";
    $c = (new B($v)); // $v is constrained to a string
}

$v = 8;
```

## なぜこれが悪いのか

psalmは`$c` のタイプを理解していない。

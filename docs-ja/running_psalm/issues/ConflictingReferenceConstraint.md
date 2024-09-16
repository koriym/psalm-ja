# ConflictingReferenceConstraint
参照渡し変数が、ifの異なるブランチで異なる型に設定される場合に発生します。

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
    $c = (new A($v)); // $vはintに制約されます
} else {
    $v = "hello";
    $c = (new B($v)); // $vはstringに制約されます
}

$v = 8;
```

## なぜこれが問題なのか
Psalmは`$c`の型がどうあるべきかを理解できません。

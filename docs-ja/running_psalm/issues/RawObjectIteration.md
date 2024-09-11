# RawObjectIteration

オブジェクトのプロパティを反復処理する際に発生します。この問題が存在するのは、それが望ましくない動作である可能性があるからです（例えば、配列を反復処理するつもりだったのかもしれません）。

```php
<?php

class A {
    /** @var string|null */
    public $foo;

    /** @var string|null */
    public $bar;
}

function takesA(A $a) {
    foreach ($a as $property) {}
}
```

# RawObjectIteration
オブジェクトのプロパティを反復処理しようとした場合に発生します。この問題は、意図しない動作である可能性があるため存在します（例：配列を反復処理するつもりだった場合など）。

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

# PossibleRawObjectIteration
オブジェクトのプロパティを反復処理している可能性がある場合に発生します。[RawObjectIteration](#rawobjectiteration)と比較してください。

```php
<?php
class A {
    /** @var string|null */
    public $foo;
    /** @var string|null */
    public $bar;
}

function takesA(A $a) {
    if (rand(0, 1)) {
        $a = [1, 2, 3];
    }
    foreach ($a as $property) {}
}
```

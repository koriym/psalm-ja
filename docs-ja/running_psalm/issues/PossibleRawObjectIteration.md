#可能なRawObjectIteration

オブジェクトのプロパティを反復処理する際に発せられる[RawObjectIteration](#rawobjectiteration) との比較。

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

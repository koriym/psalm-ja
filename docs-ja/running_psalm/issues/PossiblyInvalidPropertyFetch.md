# プロパティが無効である可能性があります。

オブジェクトでない可能性のある値、または目的のプロパティを持たないオブジェクトである可能性のある値のプロパティをフェッチしようとしたときに発行されます。

```php
<?php

class A {
    /** @var ?string */
    public $bar;
}

/** @return A|int */
function foo() {
    return rand(0, 1) ? new A : 5;
}

$a = foo();
echo $a->bar;
```

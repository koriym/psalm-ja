# PossiblyInvalidPropertyAssignment（無効なプロパティの割り当ての可能性

オブジェクトでない可能性のある値、または目的のプロパティを持たないオブジェクトである可能性のある値にプロパティを割り当てようとしたときに発行されます。

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
$a->bar = "5";
```

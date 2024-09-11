# PossiblyNullPropertyAssignment（ポッシブル・ヌル・プロパティ・アサインメント

NULLの可能性があるオブジェクトにプロパティを割り当てようとしたときに発行されます。

```php
<?php

class A {
    /** @var ?string */
    public $foo;
}
function foo(?A $a) : void {
    $a->foo = "bar";
}
```

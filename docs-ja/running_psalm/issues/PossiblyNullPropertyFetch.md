# PossiblyNullPropertyFetch

NULL の可能性があるオブジェクトのプロパティを取得しようとしたときに発行されます。

```php
<?php

class A {
    /** @var ?string */
    public $foo;
}
function foo(?A $a) : void {
    echo $a->foo;
}
```

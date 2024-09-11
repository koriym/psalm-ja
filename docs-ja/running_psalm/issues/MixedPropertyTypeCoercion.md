# MixedPropertyTypeCoercion

Psalmが配列/iterable引数の型制約の一部が満たされることを確信できない場合に発行される。

```php
<?php

class A {
    /** @var string[] */
    public $takesStringArray = [];
}

function foo(A $a, array $arr) : void {
    $a->takesStringArray = $arr;
}
```

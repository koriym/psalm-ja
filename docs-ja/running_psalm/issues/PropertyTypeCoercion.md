# PropertyTypeCoercion

プロパティが期待する型よりも小さい特定の型を持つ値でプロパティを設定するときに発行されます。

```php
<?php

class A {}
class B extends A {}

function takesA(C $c, A $a) : void {
    $c->b = $a;
}

class C {
    /** @var ?B */
    public $b;
}
```

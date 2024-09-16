# PropertyTypeCoercion
プロパティが期待する型よりも具体的でない型の値でプロパティを設定しようとした場合に発生します。

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

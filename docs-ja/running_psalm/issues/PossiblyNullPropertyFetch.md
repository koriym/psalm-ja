# PossiblyNullPropertyFetch
nullの可能性のあるオブジェクトのプロパティを取得しようとした場合に発生します。

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

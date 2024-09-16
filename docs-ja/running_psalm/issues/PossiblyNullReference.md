# PossiblyNullReference
nullの可能性のある値に対してメソッドを呼び出そうとした場合に発生します。

```php
<?php
class A {
    public function bar() : void {}
}

function foo(?A $a) : void {
    $a->bar();
}
```

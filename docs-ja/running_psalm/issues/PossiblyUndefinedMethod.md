# PossiblyUndefinedMethod
オブジェクトに定義されていない可能性のあるメソッドにアクセスしようとした場合に発生します。

```php
<?php
class A {
    public function bar() : void {}
}

class B {}

$a = rand(0, 1) ? new A : new B;
$a->bar();
```

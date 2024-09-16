# PossiblyInvalidMethodCall
オブジェクトでない可能性のある値にメソッドを呼び出そうとした場合に発生します。

```php
<?php
class A {
    public function bar() : void {}
}

/** @return A|int */
function foo() {
    return rand(0, 1) ? new A : 5;
}

foo()->bar();
```

# 未使用メソッド

`--find-dead-code` が有効になっており、Psalm が指定されたプライベートメソッドや関数の使用を 見つけられなかった場合に発行されます。

このメソッドがパブリック API の一部として使用されている場合は、そのクラスを`@psalm-api` でアノテーションしてください。

```php
<?php

class A {
    public function __construct() {
        $this->foo();
    }
    private function foo() : void {}
    private function bar() : void {}
}
$a = new A();
```

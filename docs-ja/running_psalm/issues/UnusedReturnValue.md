# 未使用戻り値

`--find-dead-code` が有効になっており、Psalm がプライベートメソッドの戻り値の用途を見つけられなかった場合に発行されます。

```php
<?php

class A {
    public function __construct() {
        $this->foo();
    }
    private function foo() : string {
        return "hello";
    }
}

new A();
```

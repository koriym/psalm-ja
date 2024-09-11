# 使用されない可能性のある戻り値

`--find-dead-code` が有効になっており、Psalm が public/protected メソッドの戻り値の型の用途を見つけられなかった場合に発行されます。

```php
<?php

class A {
    public function foo() : string {
        return "hello";
    }
}
(new A)->foo();
```

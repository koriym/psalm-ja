# MethodSignatureMismatch

メソッドのパラメータが親メソッドのパラメータと異なる場合、またはパラメータが親メソッドより少ない場合に発行されます。

```php
<?php

class A {
    public function foo(int $i) : void {}
}
class B extends A {
    public function foo(string $i) : void {}
}
```

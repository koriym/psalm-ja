# 未定義メソッドの可能性

オブジェクトに定義されていない可能性のあるメソッドにアクセスしようとしたときに発せられる。

```php
<?php

class A {
    public function bar() : void {}
}
class B {}

$a = rand(0, 1) ? new A : new B;
$a->bar();
```

#より具体的な実装パラメタ型

クラスがインターフェイスメソッドを実装しているが、 パラメータ型がインターフェイスメソッドのパラメータ型よりも 特殊な場合に発せられる。

```php
<?php

class A {}
class B extends A {
    public function bar(): void {}
}
class C extends A {
    public function bar(): void {}
}

class D {
    public function foo(A $a): void {}
}

class E extends D {
    /** @param B|C $a */
    public function foo(A $a): void {
        $a->bar();
    }
}
```

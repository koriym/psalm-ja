# LessSpecificImplementedReturnType
クラスがインターフェースメソッドを実装しているが、その戻り値の型がインターフェースメソッドの戻り値の型よりも具体的でない場合に発生します。

```php
<?php
class A {}
class B extends A {}

interface I {
    /** @return B[] */
    public function foo();
}

class D implements I {
    /** @return A[] */
    public function foo() {
        return [new A, new A];
    }
}
```

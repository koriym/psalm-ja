# LessSpecificImplementedReturnType

クラスがインターフェイスメソッドを実装しているが、その戻り値の型がインターフェイスメソッドの戻り値の型よりも特殊でない場合に発行されます。

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

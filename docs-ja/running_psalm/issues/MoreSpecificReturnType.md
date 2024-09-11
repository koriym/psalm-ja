# MoreSpecificReturnType

メソッドの宣言された戻り値の型が、推論されたものよりも特殊な場合に発せられる (`LessSpecificReturnStatement` と同じメソッドで発せられる)

```php
<?php

class A {}
class B extends A {}
function foo() : B {
    /** @psalm-suppress LessSpecificReturnStatement */
    return new A();
}
```

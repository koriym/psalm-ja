# InaccessibleMethod
利用可能なスコープ外からprotected/privateメソッドにアクセスしようとした場合に発生します。

```php
<?php
class A {
    protected function foo() : void {}
}
echo (new A)->foo();
```

# OverriddenMethodAccess
メソッドが親クラスのメソッドよりもアクセス制限が厳しい場合に発生します。

```php
<?php
class A {
    public function foo() : void {}
}

class B extends A {
    protected function foo() : void {}
}
```

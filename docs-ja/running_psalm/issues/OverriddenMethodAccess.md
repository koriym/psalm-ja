# OverriddenMethodAccess

メソッドが親メソッドよりもアクセスしにくい場合に発せられる

```php
<?php

class A {
    public function foo() : void {}
}
class B extends A {
    protected function foo() : void {}
}
```

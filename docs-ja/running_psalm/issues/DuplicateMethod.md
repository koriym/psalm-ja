# DuplicateMethod
メソッドが2回定義された場合に発生します。

```php
<?php
class A {
    public function foo() {}
    public function foo() {}
}
```

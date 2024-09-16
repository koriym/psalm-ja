# UnimplementedAbstractMethod
クラスが別のクラスを拡張しているが、そのすべての抽象メソッドを実装していない場合に発生します。

```php
<?php
abstract class A {
    abstract public function foo() : void;
}
class B extends A {}
```

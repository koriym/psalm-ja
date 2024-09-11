# 未実装抽象メソッド

あるクラスが別のクラスを継承しているが、そのクラスの抽象メソッドをすべて実装していない場合に発行されます。

```php
<?php

abstract class A {
    abstract public function foo() : void;
}
class B extends A {}
```

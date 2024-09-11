# UnimplementedInterfaceMethod（未実装インターフェースメソッド

クラスが`implements` インターフェースを実装しているが、そのすべてのメソッドを実装していない場合に発行されます。

```php
<?php

interface I {
    public function foo() : void;
}
class A implements I {}
```

# PossiblyUnusedMethod

`--find-dead-code` が有効になっており、Psalm が public または protected メソッドへの呼び出しを見つけられなかった場合に発行されます。

このメソッドが使用され、パブリックAPIの一部となっている場合は、そのクラスを`@psalm-api` でアノテートしてください。

```php
<?php

class A {
    public function foo() : void {}
    public function bar() : void {}
}
(new A)->foo();
```

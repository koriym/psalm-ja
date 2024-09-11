# PossiblyUnusedProperty

`--find-dead-code` が有効になっており、Psalm が特定の public/protected プロパティの使用を見つけられなかった場合に発行されます。

このプロパティが使用され、パブリック API の一部となっている場合は、そのクラスを`@psalm-api` でアノテートしてください。

```php
<?php

class A {
    /** @var string|null */
    public $foo;

    /** @var int|null */
    public $bar;
}

$a = new A();
echo $a->foo;
```

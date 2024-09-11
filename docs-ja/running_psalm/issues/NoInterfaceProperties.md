# NoInterfaceProperties

定義上、インターフェースはプロパティの定義を持っていません。

```php
<?php

interface I {}
class A implements I {
    /** @var ?string */
    public $foo;
}
function bar(I $i) : void {
    if ($i->foo) {}
}
```

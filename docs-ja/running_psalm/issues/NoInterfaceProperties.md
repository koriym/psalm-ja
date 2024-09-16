# NoInterfaceProperties
インターフェースでプロパティを取得しようとした場合に発生します。インターフェースは、定義上、プロパティの定義を持ちません。

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

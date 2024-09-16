# DuplicateProperty
クラスのプロパティが2回定義された場合に発生します。

```php
<?php
class Foo {
    public int $foo;
    public string $foo;
}

class Bar {
    public int $bar;
    public static string $bar;
}
```

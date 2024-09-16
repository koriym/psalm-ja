# OverriddenPropertyAccess
プロパティが親クラスの同名のプロパティよりもアクセス制限が厳しい場合に発生します。

```php
<?php
class A {
    /** @var string|null */
    public $foo;
}

class B extends A {
    /** @var string|null */
    protected $foo;
}
```

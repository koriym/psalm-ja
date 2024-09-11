# OverriddenPropertyAccess

あるプロパティが、親クラスの同名のプロパティよりもアクセスしにくい場合に発せられます。

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

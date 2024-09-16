# InaccessibleProperty
利用可能なスコープ外からprotected/privateプロパティにアクセスしようとした場合に発生します。

```php
<?php
class A {
    /** @return string */
    protected $foo;
}

echo (new A)->foo;
```

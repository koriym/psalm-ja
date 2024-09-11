# アクセス不能プロパティ

protected/private プロパティに、そのプロパティが利用可能な範囲外からアクセスしようとしたときに発行されます。

```php
<?php

class A {
    /** @return string */
    protected $foo;
}
echo (new A)->foo;
```

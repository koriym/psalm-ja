# 行方不明

存在しないクラスを参照したときに発行されます。

```php
<?php

/**
 * @psalm-suppress UndefinedClass
 */
class A extends B {}

$a = new A();
```

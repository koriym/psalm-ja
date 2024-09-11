# 拡張要件違反

trait の使用クラスが`@psalm-require-extends` を使用して指定されたクラスを拡張していない場合に発行されます。

```php
<?php

class A { }

/**
 * @psalm-require-extends A
 */
trait T { }

class B {
  // ExtensionRequirementViolation is emitted, as T requires
  // the using class B to extend A, which is not the case
  use T; 
}
```

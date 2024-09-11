# 実装要件違反

trait の使用クラスが`@psalm-require-implements` を使用して指定されたすべてのインタフェースを実装していない場合に発行されます。

```php
<?php

interface A { }
interface B { }

/**
 * @psalm-require-implements A
 * @psalm-require-implements B
 */
trait T { }

class C {
  // ImplementationRequirementViolation is emitted, as T requires
  // the using class C to implement A and B, which is not the case
  use T; 
}
```

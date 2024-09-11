#継承者違反

`@psalm-inheritors` を使用するクラス/インターフェースが、その要件を満たさないクラスによって拡張/実装されたときに発せられる。

```php
<?php

/**
 * @psalm-inheritors FooClass|BarClass
 */
class BaseClass {}
class BazClass extends BaseClass {}
// InheritorViolation is emitted, as BaseClass can only be extended
// by FooClass|BarClass, which is not the case
$a = new BazClass();
```

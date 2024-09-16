# InheritorViolation
`@psalm-inheritors`を使用するクラス/インターフェースが、その要件を満たさないクラスによって
拡張/実装された場合に発生します。

```php
<?php
/**
 * @psalm-inheritors FooClass|BarClass
 */
class BaseClass {}

class BazClass extends BaseClass {}

// InheritorViolationが発生します。BaseClassはFooClass|BarClassによってのみ
// 拡張できますが、そうではありません
$a = new BazClass();
```

# 非推奨特性

非推奨の trait を参照するときに発行されます：

```php
<?php

/** @deprecated */
trait T {}
class A {
    use T;
}
```

## なぜこれが悪いのか

通常、`@deprecated` タグは、近い将来に機能しなくなるコードを示している。

## 修正方法

非推奨の特性を使用しない。

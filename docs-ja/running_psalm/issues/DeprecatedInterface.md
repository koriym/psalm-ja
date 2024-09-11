# 非推奨インターフェース

非推奨のインターフェイスを参照するときに発せられる

```php
<?php

/** @deprecated */
interface I {}

class A implements I {}
```

## なぜこれが悪いのか

通常、`@deprecated` タグは、近い将来に機能しなくなるコードを示している。

## 修正方法

非推奨インターフェースを使わない。

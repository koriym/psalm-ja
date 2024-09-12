# DeprecatedInterface
非推奨のインターフェースを参照しようとした場合に発生します。

```php
<?php
/** @deprecated */
interface I {}
class A implements I {}
```

## なぜこれが問題なのか
`@deprecated`タグは通常、近い将来に動作しなくなるコードを示しています。

## 修正方法
非推奨のインターフェースを使用しないでください。

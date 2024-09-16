# DeprecatedClass
非推奨のクラスを参照しようとした場合に発生します：

```php
<?php
/** @deprecated */
class A {}
new A();
```

## なぜこれが問題なのか
`@deprecated`タグは通常、近い将来に動作しなくなるコードを示しています。

## 修正方法
非推奨のクラスを使用しないでください。

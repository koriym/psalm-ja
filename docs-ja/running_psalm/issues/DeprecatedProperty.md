# DeprecatedProperty
特定のクラスの非推奨プロパティを取得/設定しようとした場合に発生します。

```php
<?php
class A {
    /**
     * @deprecated
     * @var ?string
     */
    public $foo;
}

(new A())->foo = 5;
```

## なぜこれが問題なのか
`@deprecated`タグは通常、近い将来に動作しなくなるコードを示しています。

## 修正方法
非推奨のプロパティを使用しないでください。

# ImplementedParamTypeMismatch
別のクラスを継承するクラス、またはインターフェースを実装するクラスが、親のものと全く異なるdocblockパラメータ型を持つ場合に発生します。

```php
<?php
class D {
    /** @param string $a */
    public function foo($a): void {}
}

class E extends D {
    /** @param int $a */
    public function foo($a): void {}
}
```

## 修正方法
[リスコフの置換原則](https://en.wikipedia.org/wiki/Liskov_substitution_principle)を尊重するようにしてください - 親メソッドをオーバーライドするメソッドは、親メソッドと同じすべての引数を受け入れる必要があります。

```php
<?php
class D {
    /** @param string $a */
    public function foo($a): void {}
}

class E extends D {
    /** @param string|int $a */
    public function foo($a): void {}
}
```

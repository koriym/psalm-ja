# ♪ ImplementedParamTypeMismatch

他のクラスを継承したクラスや、インターフェイスを実装したクラスで、 docblockのパラメータ型が親クラスと全く異なる場合に発生します。

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

[Liskov substitution principle](https://en.wikipedia.org/wiki/Liskov_substitution_principle) 親メソッドをオーバーライドするメソッドは、親メソッドと同じ引数を受け取らなければなりません。

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

# 非推奨メソッド

指定したクラスで非推奨のメソッドを呼び出したときに返されます：

```php
<?php

class A {
    /** @deprecated */
    public function foo() : void {}
}
(new A())->foo();
```

## なぜこれが悪いのか

通常、`@deprecated` タグは、近い将来に機能しなくなるコードを示している。

## 修正方法

非推奨のメソッドを使わない。

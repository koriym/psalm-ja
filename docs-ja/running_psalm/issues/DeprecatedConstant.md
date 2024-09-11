# 非推奨定数

非推奨の定数または列挙型を参照するときに発行されます：

```php
<?php

class A {
    /** @deprecated */
    const FOO = 'foo';
}

echo A::FOO;

enum B {
    /** @deprecated */
    case B;
}

echo B::B;
```

## なぜこれが悪いのか

通常、`@deprecated` タグは、近い将来に機能しなくなるコードを示している。

## 修正方法

非推奨の定数やenumケースを使用しない。

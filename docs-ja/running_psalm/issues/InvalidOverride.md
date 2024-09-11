# 無効なオーバーライド

親クラスまたは実装されたインタフェースのメソッドをオーバーライドしないメソッドに`Override` 属性が追加された場合に発行されます。

```php
<?php

class A {
    function receive(): void
    {
    }
}

class B extends A {
    #[Override]
    function recieve(): void
    {
    }
}
```

## なぜこれが悪いのか

致命的なエラーが投げられる。

#MissingOverrideAttribute

設定フラグ`ensureOverrideAttribute` が`true` に設定され、子クラスまたはインターフェイスのメソッドが親のメソッドをオーバーライドするが、`Override` 属性が存在しない場合に発行されます。

```php
<?php

class A {
    function receive(): void
    {
    }
}

class B extends A {
    function receive(): void
    {
    }
}
```

## なぜこれが悪いのか

オーバーライドされたメソッドに`Override` 属性を付けると、意図が明確になります。詳しくは[PHP RFC](https://wiki.php.net/rfc/marking_overriden_methods) を読んでください。

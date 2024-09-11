# 無効クラス

誤ったケーシングを持つクラスを参照した場合に発生します。

```php
<?php

class Foo {}
(new foo());
```

クラスのケーシングが正しくても、名前空間の問題である可能性もあります。
```php
<?php

namespace OneTwo {
    class Three {}
}

namespace {
    use Onetwo\Three;
    //     ^ ("t" instead of "T")

    $three = new Three();
}
```

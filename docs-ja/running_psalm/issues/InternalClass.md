# 内部クラス

内部クラスとしてマークされたクラスに、無関係なネームスペースやクラスから アクセスしようとしたとき、または別のネームスペースの内部クラスとして マークされたクラスにアクセスしようとしたときに返されます。

```php
<?php

namespace A {
    /**
     * @internal
     */
    class Foo { }
}

namespace B {
    class Bat {
        public function batBat(): void {
            $a = new \A\Foo();
        }
    }
}
```

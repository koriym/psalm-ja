# 内部プロパティ

無関係なネームスペースやクラスから internal としてマーク付けされたプロパティにアクセスしようとしたとき、または異なるネームスペースから psalm-internal としてマーク付けされたプロパティにアクセスしようとしたときに発行されます。

```php
<?php

namespace A {
    class Foo {
        /**
         * @internal
         * @var ?int
         */
        public $foo;
    }
}

namespace B {
    class Bat {
        public function batBat() : void {
            echo (new \A\Foo)->foo;
        }
    }
}
```

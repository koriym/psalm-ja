# 内部メソッド

内部メソッドとしてマークされたメソッドに、関係のない名前空間やクラスから アクセスしようとしたとき、または別の名前空間の内部メソッドとしてマークされた メソッドにアクセスしようとしたときに返されます。

```php
<?php

namespace A {
    class Foo {
        /**
         * @internal
         */
        public static function barBar(): void {
        }
    }
}
namespace B {
    class Bat {
        public function batBat(): void {
            \A\Foo::barBar();
        }
    }
}
```

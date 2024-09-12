# InternalClass
関連のない名前空間またはクラスから内部としてマークされたクラスにアクセスしようとした場合、または異なる名前空間にpsalm-internalとしてマークされたクラスにアクセスしようとした場合に発生します。

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

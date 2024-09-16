# MissingImmutableAnnotation
不変（immutable）インターフェースまたはクラスを継承するクラスが`@psalm-immutable`宣言も持っていない場合に発生します。

```php
<?php
/** @psalm-immutable */
interface SomethingImmutable {
    public function someInteger() : int;
}

class MutableImplementation implements SomethingImmutable {
    private int $counter = 0;
    public function someInteger() : int {
        return ++$this->counter;
    }
}
```

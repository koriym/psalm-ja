# MissingImmutableAnnotation

不変インターフェイスまたはクラスを継承するクラスが、`@psalm-immutable` 宣言も持っていない場合に発行されます。

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

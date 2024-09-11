# ミュータブル依存性

不変クラスが、@psalm-immutableとマークされていないクラスや traitを継承した場合に発行されます。

```php
<?php

class MutableParent {
    public int $i = 0;

    public function increment() : void {
        $this->i++;
    }
}

/**
 * @psalm-immutable
 */
final class NotReallyImmutableClass extends MutableParent {}
```

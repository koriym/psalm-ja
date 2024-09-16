# MutableDependency
不変（immutable）クラスが@psalm-immutableでマークされていないクラスまたはトレイトを継承している場合に発生します。

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

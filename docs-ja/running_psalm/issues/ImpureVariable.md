# ImpureVariable
純粋（pure）なコンテキストから、不純または不純の可能性のある変数を参照しようとした場合に発生します。

```php
<?php
class A {
    public int $a = 5;

    /**
     * @psalm-pure
     */
    public function foo() : self {
        return $this;
    }
}
```

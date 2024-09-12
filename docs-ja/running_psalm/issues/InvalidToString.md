# InvalidToString
`__toString`メソッドが常に`string`を返さない場合に発生します。

```php
<?php
class A {
    public function __toString() {
        /** @psalm-suppress InvalidReturnStatement */
        return true;
    }
}
```

# 無効なEnumMethod

列挙型は、`__get` や`__toString` などのマジックメソッドのほとんどを定義していない可能性があります。

```php
<?php
enum Status: string {
    case Open = 'open';
    case Closed = 'closed';

    public function __toString(): string {
        return "SomeStatus";
    }
}
```

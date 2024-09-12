# InvalidEnumMethod
列挙型は`__get`、`__toString`などのほとんどのマジックメソッドを定義できません。

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

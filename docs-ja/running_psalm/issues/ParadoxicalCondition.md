# ParadoxicalCondition
`RedundantCondition`では捕捉できないパラドックスがプログラムのロジックで遭遇した場合に発生します。

```php
<?php
function foo(string $input) : string {
    return $input === "a" ? "bar" : ($input === "a" ? "foo" : "b");
}
```

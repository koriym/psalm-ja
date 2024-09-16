# PossiblyUnusedReturnValue
`--find-dead-code`がオンになっていて、Psalmがpublicまたはprotectedメソッドの戻り値の型の使用を見つけられない場合に発生します。

```php
<?php
class A {
    public function foo() : string {
        return "hello";
    }
}

(new A)->foo();
```

# TraitMethodSignatureMismatch
メソッドのシグネチャまたは戻り値の型が、対応するトレイトで定義されたメソッドと異なる場合に発生します。

```php
<?php
trait T {
    abstract public function foo(int $i);
}

class A {
    use T;
    public function foo(string $s) : void {}
}
```

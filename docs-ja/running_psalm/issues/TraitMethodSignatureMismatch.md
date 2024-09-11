# TraitMethodSignatureMismatch（メソッド署名の不一致

メソッドのシグネチャまたは戻り値の型が、対応する形質定義メソッドと異なる場合に発行されます。

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

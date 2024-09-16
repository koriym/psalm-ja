# ParamNameMismatch
メソッドが親メソッドをオーバーライドしているが、パラメータの名前を変更している場合に発生します。

```php
<?php
class A {
    public function foo(string $str, bool $b = false) : void {}
}

class AChild extends A {
    public function foo(string $string, bool $b = false) : void {}
}
```

## なぜこれが問題なのか
PHP 8では、[名前付きパラメータ](https://wiki

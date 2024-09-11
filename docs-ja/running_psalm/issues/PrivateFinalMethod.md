# プライベートファイナルメソッド

クラスが private final メソッドを定義したときに発行されます。PHP 8.0+ では、private final メソッドが定義されると警告が表示され (`__construct` 以外では許可されます)、 子クラスでの再定義が許可されます (`final` 修飾子は無視されます)。PHP8.0以前では、`final` 。

```php
<?php
class Foo {
    final private function baz(): void {}
}
```

## なぜこれが悪いのか

警告が表示され、バージョンによって動作が異なる。

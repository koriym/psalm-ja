# MethodSignatureMustOmitReturnType
`__clone`、`__construct`、または`__destruct`メソッドが戻り値の型を指定して定義されている場合に発生します。

```php
<?php
class A {
    public function __clone() : void {}
}
```

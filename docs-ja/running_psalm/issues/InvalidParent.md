# InvalidParent
親クラスがない場合に関数の戻り値の型が`parent`である場合に発生します。

```php
<?php
class Foo {
    public function f(): parent {}
}
```

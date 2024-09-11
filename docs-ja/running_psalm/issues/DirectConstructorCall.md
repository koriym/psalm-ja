# ダイレクトコンストラクタコール

`__construct()` 、メソッドとして直接呼び出されたときに発せられる。コンストラクタは、`new ClassName` ステートメントの結果として、暗黙的に呼び出されることになっています。

```php
<?php
class A {
    public function __construct() {}
}
$a = new A;
$a->__construct(); // wrong
```

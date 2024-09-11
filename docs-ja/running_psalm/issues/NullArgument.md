# ヌル引数

引数にNULL値を指定した関数を呼び出した際に、その関数がNULL値を期待していない場合に発生するエラー。

```php
<?php

function foo(string $s) : void {}
foo(null);
```

# NullArgument
関数が期待していない場合に、null値の引数で関数を呼び出そうとした場合に発生します。

```php
<?php
function foo(string $s) : void {}
foo(null);
```

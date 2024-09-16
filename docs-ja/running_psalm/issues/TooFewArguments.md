# TooFewArguments
関数が持つパラメータの数よりも少ない引数で関数を呼び出そうとした場合に発生します。

```php
<?php
function foo(string $a) : void {}
foo();
```

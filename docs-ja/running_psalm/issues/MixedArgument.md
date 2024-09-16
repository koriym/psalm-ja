# MixedArgument
Psalmが引数の型を判断できない場合に発生します。

```php
<?php
function takesInt(int $i) : void {}
takesInt($GLOBALS['foo']);
```

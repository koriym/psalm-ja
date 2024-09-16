# PossiblyNullFunctionCall
nullの可能性のある値に対して関数を呼び出そうとした場合に発生します。

```php
<?php
function foo(?callable $a) : void {
    $a();
}
```

# UndefinedVariable
指定された関数のスコープ内に存在しない変数を参照しようとした場合に発生します。

```php
<?php
function foo() {
    echo $a;
}
```

# 参照制約違反

参照渡し変数の型を変更したときに発せられる

```php
<?php

function foo(string &$a) {
    $a = 5;
}
```

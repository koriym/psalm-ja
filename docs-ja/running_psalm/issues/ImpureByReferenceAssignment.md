#不純な参照割り当て

無変異としてマークされた関数やメソッドの内部で、参照渡しの変数を代入するときに発行されます。

```php
<?php

/**
 * @psalm-pure
 */
function foo(string &$a): string {
    $a = "B";
    return $a;
}
```

## 修正方法

突然変異の割り当てを削除する：

```php
<?php

/**
 * @psalm-pure
 */
function foo(string &$a): string {
    return $a;
}
```

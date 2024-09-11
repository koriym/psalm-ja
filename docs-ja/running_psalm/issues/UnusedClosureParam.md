# UnusedClosureParam

`--find-dead-code` が有効になっており、Psalm がクロージャで特定のパラメータを使用できない場合に発行されます。

```php
<?php

$a = function (int $a, int $b) : int {
    return $a + 4;
};

/**
 * @param callable(int,int):int $c
 */
function foo(callable $c) : int {
    return $c(2, 4);
}
```

パラメータ名の前にアンダースコアを付けることで抑制できます：

```php
$f = function (int $_a, int $b) : int {
    return $b + 4;
};
```

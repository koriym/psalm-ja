# UnusedClosureParam
`--find-dead-code`がオンになっていて、Psalmがクロージャ内の特定のパラメータの使用を見つけられない場合に発生します。

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

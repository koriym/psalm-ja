# UnusedForeachValue
`--find-dead-code`がオンになっていて、Psalmがforeachの値への参照を見つけられない場合に発生します。

```php
<?php
/**
 * @param array<string, int> $a
 */
function foo(array $a) : void {
    foreach ($a as $key => $value) { // $valueは未使用
        echo $key;
    }
}
```

変数名の前にアンダースコアを付けるか、`$_`という名前にすることで抑制できます：

```php
<?php
foreach ([1, 2, 3] as $key => $_val) {}
foreach ([1, 2, 3] as $key => $_) {}
```

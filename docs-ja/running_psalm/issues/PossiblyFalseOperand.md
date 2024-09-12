# PossiblyFalseOperand
可能性のある`false`値を操作の一部として使用した場合（例：`+`、`.`、`^`など）に発生します。

```php
<?php
function echoCommaPosition(string $str) : void {
    echo 'The comma is located at ' . strpos($str, ',');
}
```

## 修正方法
追加のロジックで`false`値を検出することができます：

```php
<?php
function echoCommaPosition(string $str) : void {
    $pos = strpos($str, ',');
    if ($pos === false) {
        echo 'There is no comma in the string';
    }
    echo 'The comma is located at ' . $pos;
}
```

あるいは、この問題を抑制するために三項演算子を使用することもできます：

```php
<?php
function echoCommaPosition(string $str) : void {
    echo 'The comma is located at ' . (strpos($str, ',') ?: '');
}
```

# 可能性のある偽のオペランド

`false` `+` 、`.` 、`^` など）。

```php
<?php

function echoCommaPosition(string $str) : void {
    echo 'The comma is located at ' . strpos($str, ','); 
}
```

## 修正方法

ロジックを追加することで、`false` の値を検出することができます：

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

あるいは、三項演算子を使ってこの問題を抑制することもできます：

```php
<?php

function echoCommaPosition(string $str) : void {
    echo 'The comma is located at ' . (strpos($str, ',') ?: ''); 
}
```

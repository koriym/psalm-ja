# 汚染されたインクルード

ユーザー制御の入力を`include` または`require` 式に渡すことができる場合に発せられる。

信頼できないユーザ入力を`include` 呼び出しに渡すことは、攻撃者がサーバ上で任意のスクリプトを実行できるようにする可能性があり、危険です。

```php
<?php

$name = $_GET["name"];

includeCode($name);

function includeCode(string $name) : void {
    include($name . '.php');
}
```

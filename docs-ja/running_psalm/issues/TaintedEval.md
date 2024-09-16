# TaintedEval
ユーザー制御の入力が`eval`呼び出しに渡される可能性がある場合に発生します。
信頼できないユーザー入力を`eval`呼び出しに渡すことは危険です。任意のデータがサーバー上で実行される可能性があります。

```php
<?php
$name = $_GET["name"];
evalCode($name);

function evalCode(string $name) {
    eval($name);
}
```

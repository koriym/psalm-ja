# TaintedInclude
ユーザー制御の入力が`include`または`require`式に渡される可能性がある場合に発生します。
信頼できないユーザー入力を`include`呼び出しに渡すことは危険です。攻撃者がサーバー上で任意のスクリプトを実行できる可能性があります。

```php
<?php
$name = $_GET["name"];
includeCode($name);

function includeCode(string $name) : void {
    include($name . '.php');
}
```

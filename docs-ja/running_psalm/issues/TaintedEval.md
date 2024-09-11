# 汚染された評価

ユーザー制御の入力が`eval` 呼び出しに渡される可能性がある場合に発せられる。

信頼できないユーザー入力を`eval` 呼び出しに渡すことは危険である。

```php
<?php

$name = $_GET["name"];

evalCode($name);

function evalCode(string $name) {
    eval($name);
}
```

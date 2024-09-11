# テインテッド・シェル

ユーザー制御の入力が`exec` 呼び出しなどに渡される場合に発せられる。

```php
<?php

$command = $_GET["command"];

runCode($command);

function runCode(string $command) {
    exec($command);
}
```

# TaintedCallable

汚染されたテキストが任意の関数呼び出しで使用されたときに発せられる。

これは、任意の関数を実行するような危険な状況につながる可能性があります。

```php
<?php

$name = $_GET["name"];

evalCode($name);

function evalCode(string $name) {
    if (is_callable($name)) {
        $name();
    }
}
```

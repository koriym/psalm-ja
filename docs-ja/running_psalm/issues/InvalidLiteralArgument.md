# 無効なリテラル引数

`strpos` `$haystack` の第1引数のように、変数が期待される場所にリテラル引数が渡された場合に発せられる。

```php
<?php

function foo(string $s) : void {
    echo strpos(".", $s);
}
```

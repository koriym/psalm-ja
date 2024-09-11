# 偽の可能性

関数の引数が`false` である可能性があるが、関数が`false` を期待していない場合に発せられる。これは、関数の引数が`bool` の可能性があり、`PossiblyInvalidArgument` になる場合とは異なります。

```php
<?php

function foo(string $s) : void {
    $a_pos = strpos($s, "a");
    echo substr($s, $a_pos);
}
```

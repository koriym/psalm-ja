# 無効なスカラー引数

別のスカラー型を期待したメソッドにスカラー値が渡されたときに発せられる。

これは、PHP があるスカラー型を別のスカラー型に強制しようとしたと確信できる場合にのみ発生します。

それ以外の場合は`InvalidArgument` を返します。

```php
<?php

function foo(int $i) : void {}
function bar(string $s) : void {
    if (is_numeric($s)) {
        foo($s);
    }
}
```

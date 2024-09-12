# InvalidScalarArgument
別のスカラー型を期待するメソッドにスカラー値が渡された場合に発生します。
これは、PHPが1つのスカラー型を別のスカラー型に強制しようとすることが確実な状況でのみ発生します。
それ以外のすべての場合では`InvalidArgument`が発生します。

```php
<?php
function foo(int $i) : void {}

function bar(string $s) : void {
    if (is_numeric($s)) {
        foo($s);
    }
}
```

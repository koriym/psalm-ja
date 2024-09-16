# ContinueOutsideLoop
ループコンテキスト外で`continue`文に遭遇した場合に発生します。

```php
<?php
$a = 5;
continue;
```

## なぜこれが問題なのか
このコードはPHP 5.6以上ではコンパイルされません。

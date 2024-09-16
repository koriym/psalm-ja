# UndefinedTrace
未定義の変数をトレースしようとした場合に発生します。

```php
<?php
/** @psalm-trace $x */
echo 'Hello World!';
```

## 修正方法
既存の変数を提供するか、削除してください。

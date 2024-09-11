# 未定義トレース

未定義変数のトレースを試みる

```php
<?php

/** @psalm-trace $x */
echo 'Hello World!';
```

## 修正方法

既存の変数を提供するか、削除する

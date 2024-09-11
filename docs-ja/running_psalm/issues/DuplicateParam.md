# DuplicateParam

関数のパラメータが二重に定義されている場合に発せられる

```php
<?php

function foo(int $b, string $b) {}
```

## なぜこれが悪いのか

上記のコードはPHPで致命的なエラーを発生させる。

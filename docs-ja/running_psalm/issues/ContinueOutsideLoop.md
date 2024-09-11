# ContinueOutsideLoop

ループコンテキストの外側で`continue` ステートメントに遭遇したときに発せられる。

```php
<?php

$a = 5;
continue;
```

## なぜこれが悪いのか

このコードは PHP 5.6 以上ではコンパイルできません。

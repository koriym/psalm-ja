# FalseOperand

`false` を操作の一部として使用する際に発せられる（例：`+` 、`.` 、`^` など）。

```php
<?php

echo 5 . false; 
```

## なぜこれが悪いのか

`false` このような操作では意味がない

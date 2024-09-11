# 解決不能なインクルード

Psalm が、PHP がどのファイルをインクルード/要求しているのか 判断できない場合に発生します。

```php
<?php

function requireFile(string $s) : void {
    require_once $s;
}
```

# チェックタイプ

変数が特定の型にマッチするかどうかをチェックする。[Trace](./Trace.md) と似ていますが、型が期待される型と一致しない場合にのみ表示されます。

```php
<?php

/** @psalm-check-type $x = 1 */
$x = 2; // Checked variable $x = 1 does not match $x = 2
```


```php
<?php

/** @psalm-check-type-exact $x = int */
$x = 2; // Checked variable $x = int does not match $x = 2
```

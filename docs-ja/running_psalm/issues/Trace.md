# トレース

特に問題ではない。[`@psalm-trace`](../annotating_code/supported_annotations.md#psalm-trace) を使用する際に変数の型を報告するだけです。

```php
<?php

/** @psalm-trace $x */
$x = getmypid();
```

## 修正方法

本番用ではなく、デバッグ用に使用してください。

# 無効グローバル

想定していないグローバルキーワードへの参照があった場合に発行される。

```php
<?php

global $e;
```

ファイルが非グローバル・スコープからインクルードされている場合、この問題を抑制する必要があります。ファイルやディレクトリレベルでこの問題を抑制する方法については、[Config suppression](../dealing_with_code_issues/#suppressing-issues) を参照してください。

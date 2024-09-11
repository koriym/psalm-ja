# 無効なキャッチ

`Exception` を継承していないクラス/インターフェイスをキャッチしようとしたときや`Throwable`

```php
<?php

class A {}
try {
    $worked = true;
}
catch (A $e) {}
```

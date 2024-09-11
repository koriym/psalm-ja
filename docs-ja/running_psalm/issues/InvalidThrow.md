# 無効なスロー

`Exception` を継承していないクラスや、 を実装していないクラスをスローしようとしたときに発生します。`Throwable`

```php
<?php

class A {}
throw new A();
```

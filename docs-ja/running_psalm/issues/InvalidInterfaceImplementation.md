# 無効なインターフェースの実装

実装できないインターフェース(例:`Throwable`,`UnitEnum`,`BackedEnum`)を実装しようとしたときに発せられる。

```php
<?php

class E implements UnitEnum 
{
    public static function cases(): array 
    {
        return []; 
    }
}
```

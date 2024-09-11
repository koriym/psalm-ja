# PossiblyNullArrayOffset

NULL の可能性があるオフセットを使用して配列の値にアクセスしようとしたときに返されます。

```php
<?php

function foo(?int $a) : void {
    echo [1, 2, 3, 4][$a];
}
```

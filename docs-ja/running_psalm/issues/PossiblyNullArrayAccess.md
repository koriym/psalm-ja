# PossiblyNullArrayAccess

配列のオフセットに NULL の可能性がある値でアクセスしようとしたときに発生します。

```php
<?php

function foo(?array $a) : void {
    echo $a[0];
}
```

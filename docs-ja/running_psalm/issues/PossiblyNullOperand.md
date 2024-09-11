# PossiblyNullOperand

操作の一部として`null` の可能性のある値を使用する場合に発せられる (例:`+`,`.`,`^` など)。

```php
<?php

function foo(?int $a) : void {
    echo $a + 5;
}
```

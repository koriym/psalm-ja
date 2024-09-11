# LessSpecificReturnType

戻り値の型が、関数自身よりも多くの可能性を持つ場合に返されます。

```php
<?php

function foo() : ?int {
    return 5;
}
```

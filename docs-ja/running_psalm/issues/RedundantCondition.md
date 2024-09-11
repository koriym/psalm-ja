# 冗長条件

前のアサーションから条件が冗長である場合に発行されます。

```php
<?php

class A {}
function foo(A $a) : ?A {
    if ($a) return $a;
    return null;
}
```

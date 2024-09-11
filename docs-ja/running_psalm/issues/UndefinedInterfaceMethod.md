# UndefinedInterfaceMethod

インターフェイス上に存在しないメソッドを呼び出したときに出力されます。

```php
<?php

interface I {}

function foo(I $i) {
    $i->bar();
}
```

# ミックスド・オペランド

Psalmが計算式のオペランドの型を推測できない場合に返されます。

```php
<?php

echo $GLOBALS['foo'] + "hello";
```

## なぜ悪いのか

混合オペランドは致命的な結果をもたらすことがある：

```php
<?php

function foo(mixed $m) {
    echo $m . 'bar';
}

class A {}

foo(new A()); // triggers fatal error
```

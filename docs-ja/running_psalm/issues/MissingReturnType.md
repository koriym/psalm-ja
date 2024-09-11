# MissingReturnType

関数に戻り値の型が定義されていない場合に返されます。

```php
<?php

function foo() {
    return "foo";
}
```

で修正：

```php
<?php

function foo() : string {
    return "foo";
}
```

# MissingClosureParamType

クロージャパラメータに型情報が関連付けられていない場合に発行されます。

```php
<?php

$a = function($a): string {
    return "foo";
};
```

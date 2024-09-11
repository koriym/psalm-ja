# InvalidDocblockParamName

`array`docblockパラメータ名が関数内の名前付きパラメータと一致しない場合に発行されます。

```php
<?php

/**
 * @param string[] $bar
 */
function foo(array $barb): void {
    //
}
```

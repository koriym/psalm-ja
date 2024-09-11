# UnusedDocblockParam

`--find-dead-code` が有効になっており、docblock で指定されたパラメータが関数 / メソッドのシグネチャに対応するパラメータを持っていない場合に発行されます。

```php
<?php

/**
 * @param string $legacy_param was renamed to $newParam
 */
function f(string $newParam): string {
    return strtolower($newParam);
}
```

# MismatchingDocblockParamType
関数のdocblockの`@param`エントリがパラメータの型ヒントと一致しない場合に発生します。

```php
<?php
/**
 * @param int $b
 */
function foo(string $b) : void {}
```

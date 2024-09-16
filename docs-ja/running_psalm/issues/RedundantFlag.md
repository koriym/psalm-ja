# RedundantFlag
フラグが冗長な場合に発生します。例えば、デフォルトオプションが指定されている場合、FILTER_NULL_ON_FAILUREは何も効果がありません。

```php
<?php
$x = filter_input(INPUT_GET, 'hello', FILTER_VALIDATE_DOMAIN, array('options' => array('default' => 'world.com'), 'flags' => FILTER_NULL_ON_FAILURE));
```

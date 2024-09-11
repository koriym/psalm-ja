# 冗長フラグ

フラグが冗長な場合に発せられる。 例: FILTER_NULL_ON_FAILURE は、デフォルトオプションが指定されている場合、何もしない。

```php
<?php
$x = filter_input(INPUT_GET, 'hello', FILTER_VALIDATE_DOMAIN, array('options' => array('default' => 'world.com'), 'flags' => FILTER_NULL_ON_FAILURE));
```

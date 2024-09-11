# UndefinedPropertyFetch

そのプロパティが定義されていないオブジェクトのプロパティを取得するときに発行されます。

```php
<?php

class A {}
$a = new A();
echo $a->foo;
```

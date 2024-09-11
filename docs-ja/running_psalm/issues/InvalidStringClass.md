# 無効な文字列クラス

コンフィグに`allowStringToStandInForClass="false"` 、クラスを直接呼び出す代わりに文字列を渡している場合に発行される。

```php
<?php

class Foo {}
$a = "Foo";
new $a();
```

# InvalidStringClass
設定で`allowStringToStandInForClass="false"`があり、クラスを直接呼び出す代わりに文字列を渡している場合に発生します。

```php
<?php
class Foo {}
$a = "Foo";
new $a();
```

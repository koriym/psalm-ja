# UndefinedInterface
存在しないインターフェースを参照しようとしたが、同じ名前のクラスが存在する場合に発生します。

```php
<?php
class C {}
interface I extends C {}
```

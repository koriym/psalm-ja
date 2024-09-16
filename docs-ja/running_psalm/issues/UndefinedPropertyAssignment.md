# UndefinedPropertyAssignment
定義されていないプロパティをオブジェクトに割り当てようとした場合に発生します。

```php
<?php
class A {}
$a = new A();
$a->foo = "bar";
```

# MissingDependency
存在しないクラスを参照しようとした場合に発生します。

```php
<?php
/** @psalm-suppress UndefinedClass */
class A extends B {}
$a = new A();
```

# UndefinedPropertyFetch
そのプロパティが定義されていないオブジェクトからプロパティを取得しようとした場合に発生します。

```php
<?php
class A {}
$a = new A();
echo $a->foo;
```

# UnusedClass
`--find-dead-code`がオンになっていて、Psalmが特定のクラスの使用を見つけられない場合に発生します。
このクラスが使用されており、公開APIの一部である場合は、`@psalm-api`でアノテーションを付けてください。

```php
<?php
class A {}
class B {}
$a = new A();
```

# InvalidExtendClass
finalクラス、`@final`でアノテーションされたクラス、または@psalm-inheritorsを使用し、継承リストにないクラスを拡張しようとした場合に発生します。

```php
<?php
final class A {}
class B extends A {}

/**
 * @final
 */
class DoctrineA {}
class DoctrineB extends DoctrineA {}

/**
 * @psalm-inheritors A|B
 */
class C {}
class D extends C {}
```

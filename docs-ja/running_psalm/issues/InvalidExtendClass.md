# 無効な拡張クラス

finalクラス、`@final` でアノテーションされたクラス、 @psalm-inheritorsを使用していて継承リストにないクラスを継承しようとしたときに発行されます。

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
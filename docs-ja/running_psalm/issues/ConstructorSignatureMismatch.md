# コンストラクタ署名の不一致

コンストラクタのパラメータが親コンストラクタのパラメータと異なる場合、 あるいはパラメータが親コンストラクタより少なく、かつ親クラスに`@psalm-consistent-constructor` アノテーションがある場合に発行されます。

```php
<?php

/**
 * @psalm-consistent-constructor
 */
class A {
    public function __construct(int $i) {}
}
class B extends A {
    public function __construct(string $s) {}
}
```

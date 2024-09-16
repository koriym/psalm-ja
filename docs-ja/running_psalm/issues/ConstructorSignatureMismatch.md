# ConstructorSignatureMismatch
コンストラクタのパラメータが親クラスのコンストラクタのパラメータと異なる場合、または親クラスのコンストラクタよりもパラメータが少ない場合に発生します。ただし、親クラスに`@psalm-consistent-constructor`アノテーションがある場合に限ります。

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

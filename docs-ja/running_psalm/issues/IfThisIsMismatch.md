# IfThisIsMismatch

`@psalm-if-this-is` アノテーションの型が、オブジェクトの実際の型に含まれない場合に発せられる。

```php
<?php

/**
 * @template T
 */
class a {
    /**
     * @var T
     */
    private $data;
    /**
     * @param T $data
     */
    public function __construct($data) {
        $this->data = $data;
    }
    /**
     * @psalm-if-this-is a<int>
     */
    public function test(): void {
    }
}

$i = new a(123);
$i->test();

$i = new a("test");
// IfThisIsMismatch - Class is not a<int> as required by psalm-if-this-is
$i->test();
```
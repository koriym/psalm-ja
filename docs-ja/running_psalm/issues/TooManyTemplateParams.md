# ♪ TooManyTemplateParams

`@extends`/`@implements` アノテーションを使用してクラスを拡張し、型が多すぎる場合に発行されます。

```php
<?php

/**
 * @template-implements IteratorAggregate<int, string, int>
 */
class SomeIterator implements IteratorAggregate
{
    public function getIterator() {
        yield 5;
    }
}
```

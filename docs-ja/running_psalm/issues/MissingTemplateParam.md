# MissingTemplateParam

`@extends`/`@implements` アノテーションを使用してクラスを拡張する際に、テンプレート・パラメータをすべて拡張しない場合に発行されます。

```php
<?php

/**
 * @template-implements ArrayAccess<int>
 */
class SomeIterator implements ArrayAccess
{
    public function offsetSet($offset, $value) {
    }

    public function offsetExists($offset) {
        return false;
    }

    public function offsetUnset($offset) {
    }

    public function offsetGet($offset) {
        return null;
    }
}
```

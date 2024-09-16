# MissingTemplateParam
`@extends`/`@implements`アノテーションを使用してクラスを拡張する際に、そのすべてのテンプレートパラメータを拡張せずに使用した場合に発生します。

```php
<?php
/** 
 * @template-implements ArrayAccess<int> 
 */
class SomeIterator implements ArrayAccess {
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

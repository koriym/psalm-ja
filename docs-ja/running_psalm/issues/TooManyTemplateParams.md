# TooManyTemplateParams
`@extends`/`@implements`アノテーションを使用してクラスを拡張する際に、多すぎる型を追加した場合に発生します。

```php
<?php
/** 
 * @template-implements IteratorAggregate<int, string, int> 
 */
class SomeIterator implements IteratorAggregate {
    public function getIterator() {
        yield 5;
    }
}
```

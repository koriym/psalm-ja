# InvalidTraversableImplementation
クラスがTraversableを正しく実装していない場合に発生します。Traversableは`IteratorAggregate`または`Iterator`を実装することで実装する必要があります。

```php
<?php
/**
 * @implements Traversable<mixed, mixed>
 */
final class C implements Traversable {} // 致命的エラーを引き起こします
```

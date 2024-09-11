# 無効なトラバーサブル実装

クラスが正しく Traversable を実装していない場合に発生します。Traversableは、`IteratorAggregate` または`Iterator`

```php
<?php

/**
 * @implements Traversable<mixed, mixed>
 */
final class C implements Traversable {} // will cause fatal error
```

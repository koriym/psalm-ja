# ♪ UnusedPsalmSuppress

`--find-unused-psalm-suppress` がオンになっており、Psalm が指定された`@psalm-suppress` アノテーションの使用を見つけられなかった場合に発せられる。

```php
<?php

/** @psalm-suppress InvalidArgument */
echo strlen("hello");
```

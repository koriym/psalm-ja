# 無効テンプレートパラメタ

`@extends`/`@implements` アノテーションを使用して、テンプレート・タイプの制約を持つクラスを拡張したときに発行されます。

```php
<?php

/**
 * @template T of object
 */
class Base {}

/** @template-extends Base<int> */
class SpecializedByInheritance extends Base {}
```

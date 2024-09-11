# UnsupportedReferenceUsage（サポートされていない参照用法

Psalm が現在追跡できない参照 (例えば、配列オフセットの配列オフセットへの参照:`$foo = &$bar[$baz[0]]`) に遭遇したときに発行されます。サポートされていない参照に遭遇すると、Psalmはこの警告を発し、その変数が実際には参照でないかのように扱います。

## 修正方法

一時変数を使用することで解決できる場合があります：

```php
<?php

/** @var non-empty-list<int> */
$bar = [1, 2, 3];
/** @var non-empty-list<int> */
$baz = [1, 2, 3];

$foo = &$bar[$baz[0]];
```

を

```php
<?php

/** @var non-empty-list<int> */
$bar = [1, 2, 3];
/** @var non-empty-list<int> */
$baz = [1, 2, 3];

$offset = $baz[0];
$foo = &$bar[$offset];
```

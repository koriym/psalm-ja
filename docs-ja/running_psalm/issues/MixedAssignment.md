# ミックスアサインメント

Psalm が`mixed` よりも具体的な型を推測できない値に、アノテーションされていない変数を代入する際に発せられる。

```php
<?php

$a = $GLOBALS['foo'];
```

## 修正方法

`assert` ：

```php
<?php

$a = $GLOBALS['foo'];
assert(is_string($a));
```

または、明示的なキャストを追加する：

```php
<?php

$a = (string) $GLOBALS['foo'];
```

またはdocblockを追加する

```php
<?php

/** @var string */
$a = $GLOBALS['foo'];
```

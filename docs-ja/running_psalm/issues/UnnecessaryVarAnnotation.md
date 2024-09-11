# UnnecessaryVarアノテーション

`--find-dead-code` 、Psalmがすでに型を特定している代入で`@var` 。

```php
<?php

function foo() : string {
    return "hello";
}

/** @var string */
$a = foo();
```

# 未使用関数呼び出し

`--find-dead-code` がオンになっており、Psalm が返り値がどこにも使用されていない関数コールを見つけたときに発行されます。

```php
<?php

$a = strlen("hello");
strlen("goodbye"); // unused
echo $a;
```

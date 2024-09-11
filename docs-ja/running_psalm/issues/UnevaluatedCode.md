# 未評価コード

`--find-dead-code` がオンになっており、Psalm が評価されないコードに遭遇したときに発せられる。

```php
<?php

function foo() : void {
    return;
    $a = "foo";
}
```

# MixedPropertyFetch

Psalmが型を推測できない値のプロパティを取得するときに発行されます。

```php
<?php

/** @param mixed $a */
function foo($a) : void {
    echo $a->foo;
}
```

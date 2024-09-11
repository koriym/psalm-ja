# ♪ MixedPropertyAssignment

Psalmが型を推測できない値にプロパティを代入するときに発せられる

```php
<?php

/** @param mixed $a */
function foo($a) : void {
    $a->foo = "bar";
}
```

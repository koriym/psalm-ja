# PossiblyNullReference

NULL の可能性がある値に対してメソッドを呼び出そうとしたときに返されます。

```php
<?php

class A {
    public function bar() : void {}
}
function foo(?A $a) : void {
    $a->bar();
}
```

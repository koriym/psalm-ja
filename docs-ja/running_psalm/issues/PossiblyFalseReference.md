# 参考文献の可能性

である可能性のある値に対してメソッドを呼び出す際に発せられます。`false`

```php
<?php

class A {
    public function bar() : void {}
}

/** @return A|false */
function foo() {
    return rand(0, 1) ? new A : false;
}

foo()->bar();
```

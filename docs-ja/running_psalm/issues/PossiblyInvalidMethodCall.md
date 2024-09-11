# メソッドコールの可能性

オブジェクトではない可能性のある値に対してメソッドを呼び出そうとしたときに発せられる

```php
<?php

class A {
    public function bar() : void {}
}

/** @return A|int */
function foo() {
    return rand(0, 1) ? new A : 5;
}

foo()->bar();
```

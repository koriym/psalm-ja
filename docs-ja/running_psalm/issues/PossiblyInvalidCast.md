# 無効キャストの可能性

キャストできない可能性のある値をキャストしようとしたときに発行されます。

```php
<?php

class A {}
class B {
    public function __toString() {
        return 'hello';
    }
}
$c = (string) (rand(0, 1) ? new A() : new B());
```

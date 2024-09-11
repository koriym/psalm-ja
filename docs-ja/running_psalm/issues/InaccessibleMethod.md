# InaccessibleMethod

protected/privateメソッドに、そのスコープ外からアクセスしようとしたときに発行されます。

```php
<?php

class A {
    protected function foo() : void {}
}
echo (new A)->foo();
```

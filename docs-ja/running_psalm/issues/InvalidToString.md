# 無効な文字列

`__toString` メソッドが常に`string`

```php
<?php

class A {
    public function __toString() {
        /** @psalm-suppress InvalidReturnStatement */
        return true;
    }
}
```

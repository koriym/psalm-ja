# 非静的セルフコール

静的でない関数を静的に呼び出すときに発せられる

```php
<?php

class A {
    public function foo(): void {}

    public static function bar(): void {
        self::foo();
    }
}
```

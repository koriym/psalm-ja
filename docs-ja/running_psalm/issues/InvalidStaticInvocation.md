# 無効な静的呼び出し

インスタンス関数を静的に呼び出そうとしたときに発生します。

```php
<?php

class A {
    /** @var ?string */
    public $foo;

    public function bar() : void {
        echo $this->foo;
    }
}

A::bar();
```

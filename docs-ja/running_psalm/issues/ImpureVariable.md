# 不純変数

純粋なコンテキストから不純な変数、または不純な可能性のある変数を参照するときに発行されます。

```php
<?php

class A {
    public int $a = 5;

    /**
     * @psalm-pure
     */
    public function foo() : self {
        return $this;
    }
}
```

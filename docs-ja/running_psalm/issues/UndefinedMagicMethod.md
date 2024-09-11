# 未定義マジックメソッド

存在しないマジックメソッドを呼び出したときに発せられる

```php
<?php

/**
 * @method bar():string
 */
class A {
    public function __call(string $name, array $args) {
        return "cool";
    }
}
(new A)->foo();
```

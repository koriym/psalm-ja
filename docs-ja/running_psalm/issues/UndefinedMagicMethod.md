# UndefinedMagicMethod
存在しないマジックメソッドを呼び出そうとした場合に発生します。

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

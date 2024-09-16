# LessSpecificReturnStatement
関数に与えられた戻り値の型よりも一般的な戻り値文がある場合に発生します。

```php
<?php
class A {}
class B extends A {}

function foo() : B {
    return new A(); // ここで発生
}
```

# MismatchingDocblockReturnType
関数のdocblockの`@return`エントリが関数の戻り値の型ヒントと一致しない場合に発生します。

```php
<?php
class A {}
class B {}

/**
 * @return B // ここで発生
 */
function foo() : A {
    return new A();
}
```

ただし、以下の場合は問題ありません：

```php
<?php
class A {}
class B extends A {}

/**
 * @return B // ここで発生
 */
function foo() : A {
    return new B();
}
```

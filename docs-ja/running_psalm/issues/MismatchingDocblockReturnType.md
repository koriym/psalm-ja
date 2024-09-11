# ♪ MismatchingDocblockReturnType

関数のdocblockの`@return` エントリが関数のreturn typehintと一致しない場合に発行されます。

```php
<?php

class A {}
class B {}
/**
 * @return B // emitted here
 */
function foo() : A {
    return new A();
}
```

しかし、これは問題ありません：

```php
<?php

class A {}
class B extends A {}
/**
 * @return B // emitted here
 */
function foo() : A {
    return new B();
}
```

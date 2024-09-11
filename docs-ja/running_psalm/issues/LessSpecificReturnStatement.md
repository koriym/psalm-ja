# ♪ LessSpecificReturnStatement

リターンステートメントが、関数に指定されたリターンタイプよりも一般的である場合に発行されます。

```php
<?php

class A {}
class B extends A {}

function foo() : B {
    return new A(); // emitted here
}
```

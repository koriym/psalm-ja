# 無効な戻り値

関数のシグネチャの戻り値の型が正しくない場合に発せられる（`InvalidReturnStatement` と共に発せられることが多い）。

```php
<?php

function foo() : int {
    if (rand(0, 1)) {
        return "hello";
    }

    return 5;
}
```

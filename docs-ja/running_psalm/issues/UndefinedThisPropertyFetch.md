# UndefinedThisPropertyFetch

あるオブジェクトのメソッドで、そのオブジェクトのプロパティを取得する際に、そのようなプロパティが存在しない場合に発行されます。

```php
<?php

class A {
    function foo() {
        echo $this->foo;
    }
}
```

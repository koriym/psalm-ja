# アクセス不能クラス定数

public/private クラス定数が、呼び出し元のコンテキストからアクセスできない場合に発行されます。

```php
<?php

class A {
    protected const FOO = 'FOO';
}
echo A::FOO;
```

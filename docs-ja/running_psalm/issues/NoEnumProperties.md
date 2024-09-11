# NoEnumProperties

PHPは列挙型にユーザ定義のプロパティを定義することを許可していません。

```php
<?php

enum Status {
    public $prop;
}
```

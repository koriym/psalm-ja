# 重複プロパティ

クラスのプロパティが二重に定義されている場合に発行されます。

```php
<?php

class Foo
{
    public int $foo;
    public string $foo;
}

class Bar
{
    public int $bar;
    public static string $bar;
}
```

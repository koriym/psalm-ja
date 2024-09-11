# OverriddenInterfaceConstant

インターフェイスで宣言された定数が子プロセスでオーバーライドされた場合に 発せられる (PHP &lt; 8.1 では不正)。

```php
<?php

interface Foo
{
    public const BAR='baz';
}

interface Bar extends Foo
{
    public const BAR='foobar';
}
```

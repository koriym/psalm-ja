# OverriddenFinalConstant

Final と宣言された定数が、子クラスまたはインターフェイスでオーバーライドされたときに発行されます。

```php
<?php

class Foo
{
    /** @var string */
    final public const BAR='baz';
}

class Bar extends Foo
{
    /** @var string */
    public const BAR='foobar';
}
```

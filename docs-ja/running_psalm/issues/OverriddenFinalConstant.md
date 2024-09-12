# OverriddenFinalConstant
finalとして宣言された定数が子クラスまたはインターフェースでオーバーライドされた場合に発生します。

```php
<?php
class Foo {
    /** @var string */
    final public const BAR='baz';
}

class Bar extends Foo {
    /** @var string */
    public const BAR='foobar';
}
```

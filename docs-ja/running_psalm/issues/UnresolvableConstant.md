# 解決不能な定数

Psalm が`key-of` または`value-of` で使用されている定数を解決できない場合に発行されます。`static::CONST` は`key-of` と`value-of` では解決不可能と見なされることに注意してください。なぜなら、リテラルキーと値は子クラスによってオーバーライドされる可能性があるため解決できないからです。

```php
<?php

class Foo
{
    public const BAR = ['bar'];

    /**
     * @return value-of<self::BAT>
     */
    public function bar(): string
    {
        return self::BAR[0];
    }
}
```

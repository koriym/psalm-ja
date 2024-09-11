LessSpecificClassConstantType # LessSpecificClassConstantType

子の定数型が親の定数型よりも小さい場合に発行されます。

```php
<?php

class Foo
{
    /** @var int<1,max> */
    public const CONSTANT = 3;

    public static function bar(): array
    {
        return str_split("foobar", static::CONSTANT);
    }
}

class Bar extends Foo
{
    /** @var int */
    public const CONSTANT = -1;
}

Bar::bar(); // Error: str_split argument 2 must be greater than 0
```

この問題は、docblock型を持っていない定数をオーバーライドするときに常に現れます。Psalmは定数に対して、できる限り具体的な型を推論します。そうでなければ、Psalmは、定数がリテラル`1` 、 `int<1, max>``int`,`numeric`, など。

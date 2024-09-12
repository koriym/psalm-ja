# InvalidClassConstantType
子クラスの定数の型が親クラスの型を満たさない場合に発生します。

```php
<?php
class Foo {
    /** @var int<1,max> */
    public const CONSTANT = 3;

    public static function bar(): array
    {
        return str_split("foobar", static::CONSTANT);
    }
}

class Bar extends Foo {
    /** @var int<min,-1> */
    public const CONSTANT = -1;
}

Bar::bar(); // エラー: str_split の引数2は0より大きくなければなりません
```

この問題は、docblockの型を持たない定数をオーバーライドする場合に常に表示されます。Psalmは定数に対して可能な限り具体的な型を推論しますが、どの型制約を適用したいかを伝えるために型アノテーションを追加する必要があります。そうしないと、Psalmはその定数がリテラル`1`、`int<1, max>`、`int`、`numeric`などのどれを意味するのかを判断する方法がありません。

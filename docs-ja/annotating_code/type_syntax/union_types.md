# ユニオンの種類

`Type1|Type2|Type3` という形式の注釈は_Union Type_である。`Type1` `Type2` と`Type3` はすべて、そのユニオン・タイプの許容可能な型です。

`Type1` `Type2` と`Type3` はそれぞれ[atomic types](atomic_types.md) です。

ユニオン・タイプは、例えば3項式など、さまざまな方法で生成することができます：

```php
<?php $rabbit = rand(0, 10) === 4 ? 'rabbit' : ['rabbit'];
```

`$rabbit` `$rabbit` `string|array` は`string` か`array` のどちらかになります。この考えをユニオン・タイプで表すことができます。ユニオン型は、与えられた変数が持ちうるすべての型を表します。

PHPの組み込み関数もユニオン型を返します。`strpos` はある状況では`false` を返し、ある状況では`int` を返します。このユニオン型を`int|false` で表します。

# インターセクション型

`Type1&Type2&Type3` 形式のアノテーションは _インターセクション型_ です。任意の値は `Type1`、`Type2`、`Type3` を同時に満たす必要があります。`Type1`、`Type2`、`Type3` はすべて [アトミック型](atomic_types.md) です。

例えば、PHPUnitテストで次のステートメントの後：

```php
<?php
$hare = $this->createMock(Hare::class);
```

`$hare` は `Hare` を拡張し、`\PHPUnit\Framework\MockObject\MockObject` を実装するクラスのインスタンスになります。そのため、`$hare` は `Hare&\PHPUnit\Framework\MockObject\MockObject` と型付けされます。この構文は、値が複数のインターフェースを実装する必要がある場合はいつでも使用できます。

もう1つの使用例は、オブジェクトライクな配列をマージできるようにすることです：

```php
/** 
 * @psalm-type A=array{a: int}
 * @psalm-type B=array{b: int}
 *
 * @param A $a
 * @param B $b
 *
 * @return A&B
 */
function foo($a, $b) {
    return $a + $b;
}
```

返される型には `A` と `B` の両方のプロパティが含まれます。言い換えれば、`{a: int, b: int}` になります。

インターセクションは、*オブジェクト型* のリストのみ、または *オブジェクトライクな配列* のリストのみに対して有効です。

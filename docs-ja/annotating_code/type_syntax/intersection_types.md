# 交差点の種類

`Type1&Type2&Type3` という形式のアノテーションは_Intersection Type_である。どのような値も、`Type1` 、`Type2` 、`Type3` を同時に満たさなければならない。`Type1` `Type2` と`Type3` はすべて[atomic types](atomic_types.md) である。

たとえば、PHPUnit のテストではこのように記述します：

```php
<?php

$hare = $this->createMock(Hare::class);
```

`$hare` は`Hare` を継承し、`\PHPUnit\Framework\MockObject\MockObject` を実装したクラスのインスタンスとなります。したがって
`$hare` は`Hare&\PHPUnit\Framework\MockObject\MockObject` と型付けされる。値が複数のインターフェイスを実装する必要がある場合は、いつでもこの構文を使うことができる。
この構文を使うことができます。

もう1つの使用例は、オブジェクトのような配列をマージできることです：

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

返される型は、`A` と`B` の両方のプロパティを含む。言い換えれば、`{a: int, b: int}` となる。

交差は、*オブジェクト型*だけのリストと、*オブジェクトに似た配列*だけのリストに対してのみ有効である。

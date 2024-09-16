# NamedArgumentNotAllowed
`@no-named-arguments`を持つ関数を呼び出す際に名前付き引数が使用された場合に発生します。

```php
<?php
/** @no-named-arguments */
function foo(int $a, int $b): int {
	return $a + $b;
}

foo(a: 0, b: 1);
```

## なぜこれが問題なのか
`@no-named-arguments`アノテーションは、引数名が将来変更される可能性があり、名前付き引数を使用した関数呼び出しとの後方互換性が破壊される可能性があることを示しています。

## 修正方法
`@no-named-arguments`でアノテーションされた関数に対して名前付き引数の使用を避けてください。

```php
<?php
/** @no-named-arguments */
function foo(int $a, int $b): int {
	return $a + $b;
}

foo(0, 1);
```

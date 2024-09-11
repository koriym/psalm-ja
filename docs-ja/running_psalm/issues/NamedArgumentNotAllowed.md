# NamedArgumentNotAllowed（名前付き引数は許可されません

`@no-named-arguments` で関数を呼び出す際に名前付き引数が使用された場合に発せられる。

```php
<?php

/** @no-named-arguments */
function foo(int $a, int $b): int {
	return $a + $b;
}

foo(a: 0, b: 1);

```

## なぜこれが悪いのか

`@no-named-arguments` アノテーションは、引数名が将来変更される可能性があることを示し、更新によって名前付き引数を使用した関数呼び出しとの後方互換性が破られる可能性がある。

## 修正方法

`@no-named-arguments` でアノテーションされた関数では、名前付き引数の使用を避けてください。

```php
<?php

/** @no-named-arguments */
function foo(int $a, int $b): int {
	return $a + $b;
}

foo(0, 1);

```

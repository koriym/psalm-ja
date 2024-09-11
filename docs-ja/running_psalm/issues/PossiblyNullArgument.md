# 可能な引数

NULLの可能性がある引数で関数を呼び出したときに発生するエラー。

```php
<?php

function foo(string $s): void {}
foo(rand(0, 1) ? "hello" : null);
```

## よくある問題のケース

### 内部の関数呼び出しの使用`if`

```php
<?php

if (is_string($cat->getName()) {
    foo($cat->getName());
}
```
これは失敗する。なぜなら、`$cat->getName()` へのその後の呼び出しが常に同じ結果を与えるとは保証されていないからである。

#### 解決策

変数を使用する：
```php
<?php

$catName = $cat->getName();
if (is_string($catName) {
    foo($catName);
}
unset($catName);
```

または、関数の宣言に[`@psalm-mutation-free`](../../annotating_code/supported_annotations.md#psalm-mutation-free) 。

### の後に別の関数を呼び出す`if`

```php
<?php

if (is_string($cat->getName()) {
    changeCat();
    foo($cat->getName());
}
```
psalmは`changeCat()` が実際に`$cat` を変更したかどうかを知ることができないので、これは失敗します。

#### 解決策

* 他の関数（ここでは`changeCat()` ）の宣言にも[`@psalm-mutation-free`](../../annotating_code/supported_annotations.md#psalm-mutation-free) を追加する * 変数を使用する：上記参照

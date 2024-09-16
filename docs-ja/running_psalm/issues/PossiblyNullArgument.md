# PossiblyNullArgument
関数が期待していないにもかかわらず、nullの可能性のある値で関数を呼び出そうとした場合に発生します。

```php
<?php
function foo(string $s): void {}
foo(rand(0, 1) ? "hello" : null);
```

## よくある問題のケース
### `if`内での関数呼び出しの使用
```php
<?php
if (is_string($cat->getName()) {
    foo($cat->getName());
}
```
これは失敗します。なぜなら、`$cat->getName()`の後続の呼び出しが常に同じ結果を返すという保証がないからです。

#### 可能な解決策
変数を使用する：
```php
<?php
$catName = $cat->getName();
if (is_string($catName) {
    foo($catName);
}
unset($catName);
```
または、関数の宣言に[`@psalm-mutation-free`](../../annotating_code/supported_annotations.md#psalm-mutation-free)を追加する。

### `if`の後に別の関数を呼び出す
```php
<?php
if (is_string($cat->getName()) {
    changeCat();
    foo($cat->getName());
}
```
これは失敗します。なぜなら、psalmは`changeCat()`が実際に`$cat`を変更するかどうかを知ることができないからです。

#### 可能な解決策
* 他の関数（ここでは`changeCat()`）の宣言にも[`@psalm-mutation-free`](../../annotating_code/supported_annotations.md#psalm-mutation-free)を追加する
* 変数を使用する：上記を参照

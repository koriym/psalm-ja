# NoValue
Psalmが特定の式に対して可能なすべての型を無効にした場合に発生します。これはしばしば、Psalmがデッドコードを見つけたか、またはドキュメント化された型が網羅的でなかったことを示しています。

```php
<?php
/** 
 * @return never 
 */
function foo() : void {
    exit();
}

$a = foo(); // Psalmは$aが決して型を含まないことを知っています。なぜならfoo()は戻らないからです
```

```php
<?php
function foo() : void {
    return throw new Exception(''); //Psalmは戻り値式が決して使用されないことを検出しました
}
```

```php
<?php
function shutdown(): never {
    die('Application closed unexpectedly');
}

function foo(string $_a): void {}

foo(shutdown()); // foo()は決して呼び出されません
```

```php
<?php
$a = [];
/** @psalm-suppress TypeDoesNotContainType */
assert(!empty($a));
count($a); // 上のアサートは常に失敗します。$aがここで持つ可能性のある型はありません
```

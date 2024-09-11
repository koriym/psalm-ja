# テイントフロー

## 最適化されたテイントフロー

フレームワークを扱う場合、データフローを追跡するためにさまざまなレイヤーやサードパーティのコンポーネントが必要になることがあります。`@psalm-flow` アノテーションを使用することで、PsalmPHP はショートカットを使用し、汚染されたデータフローをより明確にすることができます。

### プロキシのヒント

```php
<?php // --taint-analysis
/**
 * @psalm-flow proxy exec($value)
 */
function process(string $value): void {}

process($_GET['malicious'] ?? '');
```

上記の例では、関数`process($value)` は PHP ネイティブ関数`exec($value)` のプロキシであり、コード実行の可能性がある (`TaintedShell`) と述べています。

**例

+`@psalm-flow proxy exec($value)` グローバル/スコープ付き関数`exec` を参照している +`@psalm-flow proxy MyClass::mySinkMethod($value)` クラスの関数/メソッドを参照している`MyClass`

### 戻り値のヒント

```php
<?php // --taint-analysis
/**
 * @psalm-flow ($value, $items) -> return
 */
function inputOutputHandler(string $value, string ...$items): string
{
    // lots of complicated magic
}

echo inputOutputHandler('first', 'second', $_GET['malicious'] ?? '');
```

上記の例では、関数パラメータ`$value` と`$items` が返り値に再び反映される。したがって、関数
`inputOutputHandler` への入力パラメータのいずれかが汚染されている場合、結果として返される値も汚染されていることになります。この例では
この例では、`echo` の使用により、`TaintedHtml` が検出されます。

### プロキシと返り値の組み合わせのヒント

```php
<?php // --taint-analysis /**  * @psalm-flow proxy exec($value)  * @psalm-flow ($value, $items) -> return  */ function handleInput(string $value, string ...$items): string {     // lots of complicated magic }

echo handleInput($_GET['malicious'] ?? '');
```

上の例では、前述の両方の例を組み合わせて、`@psalm-flow` アノテーションを複数回使用できることを示しています。
を複数回使用できることを示している。ここでは、`TaintedHtml` と`TaintedShell` の両方を検出することになる。

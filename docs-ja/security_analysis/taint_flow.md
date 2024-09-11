# 汚染フロー

## 最適化された汚染フロー

フレームワークを扱う際、データフローの追跡には異なるレイヤーや他のサードパーティコンポーネントが関与する場合があります。`@psalm-flow`アノテーションを使用することで、PsalmPHPはショートカットを取り、汚染データフローをより明示的にすることができます。

### プロキシヒント

```php
<?php // --taint-analysis
/**
 * @psalm-flow proxy exec($value)
 */
function process(string $value): void {}

process($_GET['malicious'] ?? '');
```

上の例は、関数`process($value)`がネイティブのPHP関数`exec($value)`のプロキシであることを述べています - これは潜在的にコード実行（`TaintedShell`）に脆弱です。

**例**
+ `@psalm-flow proxy exec($value)` グローバル/スコープ付き関数`exec`を参照
+ `@psalm-flow proxy MyClass::mySinkMethod($value)` クラス`MyClass`の関数/メソッドを参照

### 戻り値のヒント

```php
<?php // --taint-analysis
/**
 * @psalm-flow ($value, $items) -> return
 */
function inputOutputHandler(string $value, string ...$items): string
{
    // 複雑な魔法がたくさん
}

echo inputOutputHandler('first', 'second', $_GET['malicious'] ?? '');
```

上の例は、関数パラメータ`$value`と`$items`が戻り値に再び反映されることを述べています。したがって、関数`inputOutputHandler`への入力パラメータのいずれかが汚染されている場合、結果の戻り値も汚染されています。この例では、`echo`を使用しているため、`TaintedHtml`が検出されます。

### プロキシと戻り値のヒントの組み合わせ

```php
<?php // --taint-analysis
/**
 * @psalm-flow proxy exec($value)
 * @psalm-flow ($value, $items) -> return
 */
function handleInput(string $value, string ...$items): string
{
    // 複雑な魔法がたくさん
}

echo handleInput($_GET['malicious'] ?? '');
```

上の例は、前の2つの例を組み合わせており、`@psalm-flow`アノテーションを複数回使用できることを示しています。ここでは、`TaintedHtml`と`TaintedShell`の両方が検出されることになります。

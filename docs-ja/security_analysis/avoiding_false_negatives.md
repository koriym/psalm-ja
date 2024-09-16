# 偽陰性を避ける

## エスケープ解除ステートメント

以前にエスケープ/エンコードされたステートメントの後処理は、安全でないシナリオを引き起こす可能性があります。
`@psalm-taint-unescape <taint-type>`を使用して、これらのコンポーネントを明示的に安全でないと宣言できます。

```php
<?php
/**
 * @psalm-taint-unescape html
 */
function decode(string $str): string
{
    return str_replace(
        ['&lt;', '&gt;', '&quot;', '&apos;'],
        ['<', '>', '"', '"'],
        $str
    );
}

$safe = htmlspecialchars($_GET['text']);
echo decode($safe);
```

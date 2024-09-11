# 偽陰性を避ける

## ステートメントのアンエスケープ

以前にエスケープ/エンコードされたステートメントを後処理すると、安全でないシナリオを引き起こす可能性があります。
`@psalm-taint-unescape <taint-type>`そのようなコンポーネントを明示的に安全でないと宣言することができます。

```php
<?php

/**  * @psalm-taint-unescape html  */ function decode(string $str): string {     return str_replace(         ['&lt;', '&gt;', '&quot;', '&apos;'],         ['<', '>', '"', '"'],         $str     ); }

$safe = htmlspecialchars($_GET['text']); echo decode($safe);
```

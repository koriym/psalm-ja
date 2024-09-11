# ♪ DocblockTypeContradiction

指定されたdocblockタイプで条件が意味をなさない場合に発行されます。

```php
<?php

/**
 * @param string $s
 *
 * @return void
 */
function foo($s) {
    if ($s === null) {
        throw new \Exception('Bad input');
    }

    echo $s;
}
```

## なぜこれが悪いのか

これは、docblockの型に欠陥があるか、あるいは、Psalmによってすべての型がチェックされ、追加の実行時チェックを必要としない環境において、不必要な実行時チェックが行われている可能性があります。

## 修正方法

古いPHPコードの多くは、上記のようなチェックで予期せぬエラーを防ぐように 設定されています。

コードを新しいバージョンの PHP に移行する際には、より厳格な型ヒントを使用することもできます：

```php
<?php

function foo(string $s) : void {
    echo $s;
}
```

関数内部で`$s` が`string` 以外になることはありえないので、null チェックを削除することができます。

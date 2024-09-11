# UnhandledMatchCondition

マッチ式が1つ以上のオプションを処理できない場合に発行されます。

```php
<?php

function matchOne(): string {
    $foo = rand(0, 1) ? "foo" : "bar";

    return match ($foo) {
        'foo' => 'foo',
    };
}
```

## なぜこれが悪いのか

上記のコードは50%の確率で`UnhandledMatchError` エラーで失敗する。

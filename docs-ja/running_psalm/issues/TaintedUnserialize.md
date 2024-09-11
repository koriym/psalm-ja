# TaintedUnserialize

`unserialize` 呼び出しに対して検出された汚染された入力。

信頼できないユーザー入力を`unserialize` 呼び出しに渡すことは危険である -[PHP documentation on unserialize](https://www.php.net/manual/en/function.unserialize.php) から：

&gt; allowed_classesのオプション値に関係なく、信頼できないユーザー入力をunserialize()に渡さないこと。アンシリアライズの結果、オブジェクトのインスタンス化やオートローディングによってコードがロードされ実行される可能性があり、悪意のあるユーザーはこれを悪用できるかもしれません。シリアライズされたデータをユーザーに渡す必要がある場合は、（json_decode() や json_encode() を使って） JSON のような安全で標準的なデータ交換フォーマットを使いましょう。

```php
<?php

$command = $_GET["data"];

getObject($command);

function getObject(string $data) : object {
    return unserialize($data);
}
```

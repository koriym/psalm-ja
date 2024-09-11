# TaintedHeader

ヘッダーインジェクションの可能性。このルールは、ユーザが制御する入力を HTTP ヘッダに渡すことができる場合に発行されます。

## リスク

ヘッダーインジェクションのリスクは、あなたの環境に大きく依存します。

あなたのウェブサーバが[`XSendFile`](https://www.nginx.com/resources/wiki/start/topics/examples/xsendfile/) /[`X-Accel`](https://www.nginx.com/resources/wiki/start/topics/examples/x-accel/) のようなものをサポートしている場合、 攻撃者はシステム上の任意のファイルにアクセスできる可能性があります。

あなたのシステムがそれをしない場合、以下のような他の懸念があるかもしれません：

- クッキー・インジェクション - オープン・リダイレクト - プロキシ・キャッシュ・ポイズニング

## 例

```php
<?php

header($_GET['header']);
```

## 緩和策

攻撃者がキーではなく値のみを設定できるようにする。(例:`header('Location: ' . $_GET['target']);`)

設定された値が適切であることを確認する。許可リストの使用を検討する。(例：リダイレクトの場合)

## さらなるリソース

-[Unvalidated Redirects and Forwards Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/Unvalidated_Redirects_and_Forwards_Cheat_Sheet.html) -[OWASP Wiki for Cache Poisoning](https://owasp.org/www-community/attacks/Cache_Poisoning) -[CWE-601](https://cwe.mitre.org/data/definitions/601.html) [CWE-644](https://cwe.mitre.org/data/definitions/644.html)

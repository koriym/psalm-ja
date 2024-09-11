# 汚染されたファイル

このルールは、ユーザー制御の入力がセンシティブなファイル操作に渡される可能性がある場合に発行される。

## リスク

ここでのリスクは、ユーザー制御入力を含む実際の操作と、それが後でどのように処理されるかに依存します。

その範囲は以下の通り：

- ファイルの作成 - 例:`file_put_contents` - リスク: サーバの設定によっては、リモートでコードが実行される可能性があります。(例: ウェブルートにファイルを書き込む) - ファイルの変更 - 例:`file_put_contents` - リスク: サーバの設定によっては、リモートでコードが実行される可能性があります。(例: PHP ファイルの修正) - ファイルの読み込み - 例:`file_get_contents` - リスク: ファイルシステムから機密データが公開される可能性があります。(例: 設定値、ソースコード、ユーザが投稿したファイル) - ファイルの削除 - 例:`unlink` - リスク: サービス拒否、あるいは潜在的に RCE。(例: アプリケーションコードの削除、.htaccess ファイルの削除)

## 例

```php
<?php

$content = file_get_contents($_GET['header']);
echo $content;
```

## 緩和策

ファイル操作時の名前の検証には、可能な限りallowlistを使用する。

`..` 、`\` 、`/` 。

## さらなるリソース

-[File Upload Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/File_Upload_Cheat_Sheet.html) -[OWASP Wiki for Unrestricted FIle Upload](https://owasp.org/www-community/vulnerabilities/Unrestricted_File_Upload) [CWE-73](https://cwe.mitre.org/data/definitions/73.html)

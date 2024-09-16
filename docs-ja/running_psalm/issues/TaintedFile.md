# TaintedFile
このルールは、ユーザー制御の入力が機密性の高いファイル操作に渡される可能性がある場合に発生します。

## リスク
ここでのリスクは、実際の操作にユーザー制御の入力が含まれているかどうか、およびそれがどのように処理されるかによって異なります。
以下のようなリスクがあります：
- ファイルの作成
  - 例：`file_put_contents`
  - リスク：サーバーの設定によっては、リモートコード実行につながる可能性があります（例：ウェブルートにファイルを書き込む）
- ファイルの変更
  - 例：`file_put_contents`
  - リスク：サーバーの設定によっては、リモートコード実行につながる可能性があります（例：PHPファイルの変更）
- ファイルの読み取り
  - 例：`file_get_contents`
  - リスク：ファイルシステムから機密データが露出する可能性があります（例：設定値、ソースコード、ユーザーが提出したファイル）
- ファイルの削除
  - 例：`unlink`
  - リスク：サービス拒否（DoS）や潜在的にリモートコード実行（RCE）につながる可能性があります（例：アプリケーションコードの削除、.htaccessファイルの削除）

## 例
```php
<?php
$content = file_get_contents($_GET['header']);
echo $content;
```

## 緩和策
可能な限り、ファイル操作の名前を検証するために許可リストアプローチを使用してください。
ユーザー制御のファイル名から`..`、`\`、`/`を取り除いて無害化してください。

## さらなるリソース
- [ファイルアップロードチートシート](https://cheatsheetseries.owasp.org/cheatsheets/File_Upload_Cheat_Sheet.html)
- [制限のないファイルアップロードに関するOWASP Wiki](https://owasp.org/www-community/vulnerabilities/Unrestricted_File_Upload)
- [CWE-73](https://cwe.mitre.org/data/definitions/73.html)

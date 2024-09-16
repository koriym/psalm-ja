# TaintedCookie
潜在的なクッキーインジェクション。このルールは、ユーザー制御の入力がクッキーに渡される可能性がある場合に発生します。

## リスク
任意のクッキーを設定するリスクは、さらなるアプリケーション設定に依存します。潜在的な問題の例：
- セッション固定：認証クッキーが成功したログイン後に変更されない場合、攻撃者がセッションクッキーを固定する可能性があります。固定されたクッキーで被害者がログインすると、攻撃者はユーザーのセッションを乗っ取ることができます。
- クロスサイトスクリプティング（XSS）：一部のアプリケーションコードがクッキーを読み取り、ユーザーに対して無害化せずに出力する可能性があります。

## 例
```php
<?php
setcookie('authtoken', $_GET['value'], time() + (86400 * 30), '/');
```

## 緩和策
これが必要な機能である場合、クッキーの設定を値に限定し、名前には設定しないようにします（例の`authtoken`など）。
認証試行後にセッショントークンを変更するようにしてください。

## さらなるリソース
- [OWASPのセッション固定に関するWiki](https://owasp.org/www-community/attacks/Session_fixation)
- [セッション管理チートシート](https://cheatsheetseries.owasp.org/cheatsheets/Session_Management_Cheat_Sheet.html)
- [CWE-384](https://cwe.mitre.org/data/definitions/384.html)

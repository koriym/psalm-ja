# ♪テインテッド・クッキー

クッキー・インジェクションの可能性。このルールは、ユーザが制御する入力をクッキーに渡すことができる場合に発行されます。

## リスク

任意のクッキーを設定するリスクは、さらなるアプリケーションの設定に依存します。 

潜在的な問題の例

- セッションの固定化：認証クッキーがログイン成功後に変更されない場合、攻撃者はセッション・クッキーを固定化できます。被害者が固定化されたクッキーでログインすると、攻撃者はユーザのセッションを乗っ取ることができます。- クロスサイト・スクリプティング(XSS)：あるアプリケーション・コードはクッキーを読み、それをユーザに対して未分析のまま出力する可能性があります。



## 例

```php
<?php

setcookie('authtoken', $_GET['value'], time() + (86400 * 30), '/');
```

## 緩和策

これが必要な機能である場合、クッキーの設定を名前ではなく値に制限してください。(例:`authtoken` )

認証を試行した後は、必ずセッショントークンを変更してください。

## さらなるリソース

-[OWASP Wiki for Session fixation](https://owasp.org/www-community/attacks/Session_fixation) -[Session Management Cheatsheet](https://cheatsheetseries.owasp.org/cheatsheets/Session_Management_Cheat_Sheet.html) [CWE-384](https://cwe.mitre.org/data/definitions/384.html)

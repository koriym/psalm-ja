# 汚染されたLdap

LDAPインジェクションの可能性。このルールは、LDAPリクエストにユーザ制御の入力が渡される可能性がある 場合に発行されます。

## リスク

信頼できないユーザー入力をLDAPリクエストに渡すことは危険です。 

このような LDAP リクエストがログイン目的で使われた場合、 認証をバイパスされる可能性があります。攻撃者は、どのユーザに対しても`true` と評価するようなフィルタを書くことができ、 その結果、認証情報を簡単にブルートフォースすることができる。 


## 例

```php
<?php

$ds = ldap_connect('example.com');
$dn = 'o=Psalm, c=US';
$filter = $_GET['filter'];
ldap_search($ds, $dn, $filter, []);
```

## 緩和策

[`ldap_escape`](https://www.php.net/manual/en/function.ldap-escape.php) を使用して、LDAP フィルタおよび DN へのユーザ入力をエスケープする。


## さらなるリソース

-[OWASP Wiki for LDAP Injections](https://owasp.org/www-community/attacks/LDAP_Injection) -[LDAP Injection Prevention Cheatsheet](https://cheatsheetseries.owasp.org/cheatsheets/LDAP_Injection_Prevention_Cheat_Sheet.html) [CWE-90](https://cwe.mitre.org/data/definitions/90.html)

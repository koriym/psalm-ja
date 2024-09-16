# TaintedLdap
潜在的なLDAPインジェクション。このルールは、ユーザー制御の入力がLDAPリクエストに渡される可能性がある場合に発生します。

## リスク
信頼できないユーザー入力をLDAPリクエストに渡すことは危険である可能性があります。これらのLDAPリクエストがログイン目的で使用されている場合、認証バイパスにつながる可能性があります。攻撃者は任意のユーザーに対して`true`と評価されるフィルタを書くことができ、そのため簡単に認証情報を総当たりできる可能性があります。

## 例
```php
<?php
$ds = ldap_connect('example.com');
$dn = 'o=Psalm, c=US';
$filter = $_GET['filter'];
ldap_search($ds, $dn, $filter, []);
```

## 緩和策
LDAPフィルタとDNのユーザー入力をエスケープするには、[`ldap_escape`](https://www.php.net/manual/en/function.ldap-escape.php)を使用してください。

## さらなるリソース
- [LDAPインジェクションに関するOWASP Wiki](https://owasp.org/www-community/attacks/LDAP_Injection)
- [LDAPインジェクション防止チートシート](https://cheatsheetseries.owasp.org/cheatsheets/LDAP_Injection_Prevention_Cheat_Sheet.html)
- [CWE-90](https://cwe.mitre.org/data/definitions/90.html)

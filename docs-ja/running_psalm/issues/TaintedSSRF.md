# TaintedSSRF

サーバサイドリクエストフォージェリの脆弱性。このルールは、ユーザが制御した入力がネットワークリクエストに渡される可能性がある場合に発行されます。

## リスク

信頼できないユーザー入力をネットワークリクエストに渡すことは危険です。 

攻撃者がHTTPリクエストを完全に制御できれば、内部サービスに接続することができます。これらの性質によっては、セキュリティリスクを引き起こす可能性があります。(例えば、バックエンドサービス、管理者インターフェース、AWSメタデータなど）。

## 例

```php
<?php
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $_GET['url']);

curl_exec($ch);

curl_close($ch);
```

## 緩和策

SSRFの脆弱性を緩和するのは難しい。攻撃者は内部のDNS名を指す悪意のあるドメインを作成することができるので、IPを許可しないようにすることは、おそらくうまくいかないだろう。

考えてみてください：

1.接続可能なドメインの許可リストを持つ。2.内部リソースにアクセスできないプロキシにcURLを向ける。

## さらなるリソース

-[OWASP Wiki for Server Side Request Forgery](https://owasp.org/www-community/attacks/Server_Side_Request_Forgery) -[CWE-918](https://cwe.mitre.org/data/definitions/918)
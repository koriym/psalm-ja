# TaintedHtml

HTMLを含むユーザ制御入力が`echo` 。

## リスク

これはクロスサイトスクリプティング(XSS)の脆弱性につながる可能性があります。XSSの脆弱性を利用すると、攻撃者は悪意のあるJavaScriptを注入し、JavaScriptが実行可能なあらゆるアクションを実行することができます。例としては以下のようなものがあります：

- 認証材料(例えばクッキー、JWTトークン)の盗用 - DOMを読むことによる機密情報の流出 - ウェブサイト上のキーログの入力(例えば偽のログインフォーム)

これが悪用可能かどうかは、いくつかの条件による：

- 実行可能な mimetype が設定されているか。(例:`text/html`) - コンテンツはインラインで提供されているか、それとも添付ファイルとして提供されているか？(`Content-Disposition`) - 出力は適切にサニタイズされているか？(出力は適切にサニタイズされているか（例：すべてのHTMLタグを取り除く、または許可された文字の許可リストを持つ）。

## 例

```php
<?php

$name = $_GET["name"];

printName($name);

function printName(string $name) {
    echo $name;
}
```

## 緩和策

-`htmlentities` 、あるいはallowlistを使うなどして、ユーザー入力をサニタイズする。- すべてのクッキーを`HTTPOnly` に設定する。 - XSS脆弱性のリスクを制限するために、コンテンツセキュリティポリシー(CSP)の利用を検討する。- ユーザー入力自体がHTMLの場合は[Sanitizing HTML User Input](../../security_analysis/avoiding_false_positives.md#sanitizing-html-user-input)

## その他のリソース

-[OWASP Wiki for Cross Site Scripting (XSS)](https://owasp.org/www-community/attacks/xss/) [Content-Security-Policy - Web Fundamentals](https://developers.google.com/web/fundamentals/security/csp)

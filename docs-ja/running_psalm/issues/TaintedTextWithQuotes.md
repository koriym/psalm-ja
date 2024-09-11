# 引用符付きテキスト

引用符を含むユーザ制御の入力が`echo` 文に渡された場合に発行されます。

## リスク

これはクロスサイトスクリプティング(XSS)の脆弱性につながる可能性があります。XSSの脆弱性を利用すると、攻撃者は悪意のあるJavaScriptを注入し、JavaScriptが実行可能なあらゆるアクションを実行することができます。例としては以下のようなものがあります：

- 認証材料(例えばクッキー、JWTトークン)の盗用 - DOMを読むことによる機密情報の流出 - ウェブサイト上のキーログの入力(例えば偽のログインフォーム)

これが悪用可能かどうかは、いくつかの条件による：

- 実行可能な mimetype が設定されているか。(例:`text/html`) - コンテンツはインラインで提供されているか、それとも添付ファイルとして提供されているか？(`Content-Disposition`) - 出力は適切にサニタイズされているか？(出力は適切にサニタイズされているか（例：すべてのHTMLタグを取り除く、または許可された文字の許可リストを持つ）。

## 例

```php
<?php
$param = strip_tags($_GET['param']);
?>

<script>
    console.log('<?=$param?>')
</script>
```

ここで`GET` のパラメータとして`');alert('injection');//` を渡すと、`alert` がトリガーされる。

## 緩和策

-`htmlentities` 、`ENT_QUOTES` フラグを付ける、あるいはallowlistを使うなどして、ユーザー入力をサニタイズする。`HTTPOnly`- XSS 脆弱性のリスクを制限するために、コンテンツセキュリティポリシー(CSP)の使用を検討してください。- ユーザー入力自体がHTMLの場合は[Sanitizing HTML User Input](../../security_analysis/avoiding_false_positives.md#sanitizing-html-user-input)

## その他のリソース

-[OWASP Wiki for Cross Site Scripting (XSS)](https://owasp.org/www-community/attacks/xss/) [Content-Security-Policy - Web Fundamentals](https://developers.google.com/web/fundamentals/security/csp)

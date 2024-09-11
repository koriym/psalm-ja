# 詩篇における安全保障分析

Psalmは、ユーザが制御する入力(`$_GET['name']` のような)と、エスケープされていないユーザが制御する入力が行き着くことを望まない場所(のような)との間のつながりを見つけようとすることができます。 `echo "<h1>$name</h1>"`のような)との間のつながりを見つけようと試みることができます。

このモードは`--taint-analysis` コマンドラインフラグで有効にできます。テイント解析が有効な場合、他の解析は行われません。  [ensure comprehensive results](https://github.com/vimeo/psalm/issues/6156) 、Psalmをテイント解析の前に通常通り実行し、エラーを修正する必要があります。

汚染された入力とは、アプリケーションのユーザーによって全体的または部分的に制御される可能性のあるものです。テイント解析では、汚染された入力を「汚染源」と呼びます。

汚染源の例

 -`$_GET[‘id’]` -`$_POST['email']` `$_COOKIE['token']`

 汚染分析では、汚染源から汚染シンクにデータがどのように流れるかを追跡します。テイントシンクとは、信頼できないデータが最終的に到達することを本当に望まない場所のことです。

シンクの例

 - `<div id="section_<?= $id ?>">`  -`$pdo->exec("select * from users where name='" . $name . "'")`

## テイントタイプ

Psalmはデフォルトで[Psalm\Type\TaintKind](https://github.com/vimeo/psalm/blob/master/src/Psalm/Type/TaintKind.php) クラスで定義されたいくつかのテイントタイプを認識します：

-`sql` - SQLを含む可能性のある文字列に使用されます -`ldap` - ldapのDNやフィルタを含む可能性のある文字列に使用されます -`html` - 角括弧や引用符で囲まれていない文字列を含む可能性のある文字列に使用されます -`has_quotes` - 引用符で囲まれていない文字列を含む可能性のある文字列に使用されます -`shell` - シェルコマンドを含む可能性のある文字列に使用されます -`callable` - ユーザーが制御できる呼び出し可能な文字列に使用されます -`unserialize` - 直列化された文字列を含む可能性のある文字列に使用されます -`include` - パスが含まれる可能性のある文字列に使用されます - - コードが含まれる可能性のある文字列に使用されます`eval` - コードを含む可能性のある文字列に使用 -`ssrf` - Curl などに渡されるテキストを含む可能性のある文字列に使用 -`file` - パスを含む可能性のある文字列に使用 -`cookie` - http クッキーを含む可能性のある文字列に使用 -`header` - http ヘッダーを含む可能性のある文字列に使用 -`user_secret` - ユーザーが提供する秘密を含む可能性のある文字列に使用 -`system_secret` - システム秘密を含む可能性のある文字列に使用

カスタムテイントソースを定義する際に、独自のテイントタイプを定義することもできます。

## テイントソース

Psalmは現在3つのデフォルトテイントソースを定義しています:`$_GET`,`$_POST`,`$_COOKIE` サーバー変数です。

また、[define your own taint sources](custom_taint_sources.md).

## テイントシンク

Psalmは現在、ビルトイン関数やメソッドに対して、`echo`,`include`,`header` を含む様々なシンクを定義しています。

また、[define your own taint sinks](custom_taint_sinks.md) 。

## 偽陽性の回避

多くの偽陽性を避けたい人はいない -[here’s a guide to avoiding them](avoiding_false_positives.md).

## 制限事項

テイント解析は、値のエスケープ時にミスをしないことに依存しています。

```php
$sql = 'SELECT * FROM users WHERE id = ' . $mysqli->real_escape_string((string) $_GET['id']);

$html = "
  <img src=" . htmlentities((string) $_GET['img']) . " alt='' />
  <a href='" . htmlentities((string) $_GET['a1']) . "'>Link 1</a>
  <a href='" . htmlentities((string) $_GET['a2']) . "'>Line 2</a>";

// Details:
//    $id  = 'id'                   - Missing quotes
//    $img = '/ onerror=alert(1)'   - Missing quotes
//    $a1  = 'javascript:alert(1)'  - Normal inline JavaScript
//    $a2  = '/' onerror='alert(1)' - Pre PHP 8.1, single quotes are not escaped by default
// Test:
//    /?id=id&img=%2F+onerror%3Dalert%281%29&a1=javascript%3Aalert%281%29&a2=%2F%27+onerror%3D%27alert%281%29
```

このような問題を避けるには、SQLやコマンドにパラメータ化クエリ（例：`exec` ）を使用し、HTMLにはコンテキストを意識したテンプレート化エンジンを使用します。そして、[literal-string](https://psalm.dev/docs/annotating_code/type_syntax/scalar_types/#literal-string) 型を使用して、センシティブな文字列がアプリケーションで定義されている（つまり、開発者によって書かれている）ことを確認してください。

## ベースラインとテイント解析の併用

テイント解析は他の静的コード解析とは別に行われるため、ベースラインを別に使うことは理にかなっています。

use-baseline=PATHオプションを使用すると、テイント解析用に別のベースラインを設定できます。

## 結果をユーザーインターフェースで見る

Psalmは静的解析結果を交換するための標準規格[SARIF](http://docs.oasis-open.org/sarif/sarif/v2.0/csprd01/sarif-v2.0-csprd01.html) をサポートしています。これにより、テイントフローを含むSARIF互換のソフトウェアで解析結果を表示できます。

### GitHub コードスキャン

[GitHub code scanning](https://docs.github.com/en/free-pro-team@latest/github/finding-security-vulnerabilities-and-errors-in-your-code/about-code-scanning) は、[Psalm GitHub Action](https://github.com/marketplace/actions/psalm-static-analysis-for-php).

また、[the GitHub documentation](https://docs.github.com/en/free-pro-team@latest/github/finding-security-vulnerabilities-and-errors-in-your-code/uploading-a-sarif-file-to-github) に記載されているように、生成されたSARIFファイルを手動でアップロードすることもできます。

生成された SARIF ファイルは、リポジトリの "Security "タブで利用できます。

### その他の SARIF 互換ソフトウェア

SARIF レポートを作成するには、`--report` フラグと`.sarif` 拡張子を付けて Psalm を実行してください。例えば

```bash
psalm --report=results.sarif
```

## テイントグラフのデバッグ

PsalmはDOT言語を使ってテイントグラフを出力できます。これは期待したテイントが検出されない場合に便利です。DOTグラフを生成するには、`--dump-taint-graph` フラグを付けてPsalmを実行してください。例えば

```bash
psalm --taint-analysis --dump-taint-graph=taints.dot
dot -Tsvg -o taints.svg taints.dot
```

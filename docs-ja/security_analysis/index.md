# Psalmにおけるセキュリティ分析

Psalmは、ユーザーが制御できる入力（`$_GET['name']`など）と、エスケープされていないユーザー制御の入力が終わってほしくない場所（`echo "<h1>$name</h1>"`など）との間のつながりを、アプリケーションを通じてデータがどのように流れるか（代入、関数/メソッド呼び出し、配列/プロパティアクセスを介して）を見ることで見つけようとします。

このモードは`--taint-analysis`コマンドラインフラグで有効にできます。汚染分析が有効になると、他の分析は実行されません。[包括的な結果を確保するために](https://github.com/vimeo/psalm/issues/6156)、汚染分析の前に通常通りPsalmを実行し、エラーを修正する必要があります。

汚染された入力とは、アプリケーションのユーザーが全体的または部分的に制御できるものすべてです。汚染分析では、汚染された入力を_汚染源_と呼びます。

例えば以下のようなものが汚染源です：
- `$_GET['id']`
- `$_POST['email']`
- `$_COOKIE['token']`

汚染分析は、データが汚染源から_汚染シンク_にどのように流れるかを追跡します。汚染シンクは、信頼できないデータが決して到達してほしくない場所です。

例えば以下のようなものが汚染シンクです：
- `<div id="section_<?= $id ?>">`
- `$pdo->exec("select * from users where name='" . $name . "'")`

## 汚染タイプ

Psalmはデフォルトで、[Psalm\Type\TaintKind](https://github.com/vimeo/psalm/blob/master/src/Psalm/Type/TaintKind.php)クラスで定義されている多くの汚染タイプを認識します：

- `sql` - SQLを含む可能性のある文字列に使用
- `ldap` - LDAPのDNまたはフィルタを含む可能性のある文字列に使用
- `html` - 山括弧または引用符なしの文字列を含む可能性のある文字列に使用
- `has_quotes` - 引用符なしの文字列を含む可能性のある文字列に使用
- `shell` - シェルコマンドを含む可能性のある文字列に使用
- `callable` - ユーザーが制御可能な呼び出し可能な文字列に使用
- `unserialize` - シリアル化された文字列を含む可能性のある文字列に使用
- `include` - インクルードされるパスを含む可能性のある文字列に使用
- `eval` - コードを含む可能性のある文字列に使用
- `ssrf` - Curlまたはそれらきのテキストを渡す可能性のある文字列に使用
- `file` - パスを含む可能性のある文字列に使用
- `cookie` - HTTPクッキーを含む可能性のある文字列に使用
- `header` - HTTPヘッダーを含む可能性のある文字列に使用
- `user_secret` - ユーザーが提供した秘密を含む可能性のある文字列に使用
- `system_secret` - システムの秘密を含む可能性のある文字列に使用

カスタム汚染源を定義する際に、独自の汚染タイプを自由に定義することもできます - これらは単なる文字列です。

## 汚染源

Psalmは現在、デフォルトで3つの汚染源を定義しています：`$_GET`、`$_POST`、`$_COOKIE`サーバー変数です。

[独自の汚染源を定義する](custom_taint_sources.md)こともできます。

## 汚染シンク

Psalmは現在、`echo`、`include`、`header`を含む組み込み関数やメソッドに対して、多くの異なるシンクを定義しています。

[独自の汚染シンクを定義する](custom_taint_sinks.md)こともできます。

## 偽陽性を避ける

誰も大量の偽陽性をかき分けたくはありません - [ここに偽陽性を避けるためのガイドがあります](avoiding_false_positives.md)。

## 制限事項

汚染分析は、値のエスケープ時にミスを犯さないことに依存しています。例えば：

```php
$sql = 'SELECT * FROM users WHERE id = ' . $mysqli->real_escape_string((string) $_GET['id']);

$html = "
  <img src=" . htmlentities((string) $_GET['img']) . " alt='' />
  <a href='" . htmlentities((string) $_GET['a1']) . "'>Link 1</a>
  <a href='" . htmlentities((string) $_GET['a2']) . "'>Line 2</a>";

// 詳細：
//    $id  = 'id'                   - 引用符が不足
//    $img = '/ onerror=alert(1)'   - 引用符が不足
//    $a1  = 'javascript:alert(1)'  - 通常のインラインJavaScript
//    $a2  = '/' onerror='alert(1)' - PHP 8.1以前では、シングルクォートはデフォルトでエスケープされない
// テスト：
//    /?id=id&img=%2F+onerror%3Dalert%281%29&a1=javascript%3Aalert%281%29&a2=%2F%27+onerror%3D%27alert%281%29
```

これらの問題を避けるには、SQLとコマンド（例：`exec`）にはパラメータ化クエリを使用し、HTMLにはコンテキストを考慮したテンプレートエンジンを使用してください。そして、[literal-string](https://psalm.dev/docs/annotating_code/type_syntax/scalar_types/#literal-string)型を使用して、機密な文字列がアプリケーションで定義されている（つまり、開発者によって書かれた）ことを確認してください。

## 汚染分析でベースラインを使用する

汚染分析は他の静的コード分析とは別に実行されるため、別のベースラインを使用することが理にかなっています。

`--use-baseline=PATH`オプションを使用して、汚染分析に異なるベースラインを設定できます。

## ユーザーインターフェースで結果を表示する

Psalmは、静的分析結果を交換するための[SARIF](http://docs.oasis-open.org/sarif/sarif/v2.0/csprd01/sarif-v2.0-csprd01.html)標準をサポートしています。これにより、汚染フローを含む結果を任意のSARIF互換ソフトウェアで表示できます。

### GitHub Code Scanning

[GitHub code scanning](https://docs.github.com/en/free-pro-team@latest/github/finding-security-vulnerabilities-and-errors-in-your-code/about-code-scanning)は、[Psalm GitHub Action](https://github.com/marketplace/actions/psalm-static-analysis-for-php)を使用してセットアップできます。

または、[GitHubのドキュメント](https://docs.github.com/en/free-pro-team@latest/github/finding-security-vulnerabilities-and-errors-in-your-code/uploading-a-sarif-file-to-github)に記載されているように、生成されたSARIFファイルを手動でアップロードすることもできます。

結果は、リポジトリの「Security」タブで利用可能になります。

### その他のSARIF互換ソフトウェア

SARIFレポートを生成するには、`--report`フラグと`.sarif`拡張子を付けてPsalmを実行します。例：

```bash
psalm --report=results.sarif
```

## 汚染グラフのデバッグ

Psalmは、DOT言語を使用して汚染グラフを出力できます。これは、期待される汚染が検出されない場合に役立ちます。DOTグラフを生成するには、`--dump-taint-graph`フラグを付けてPsalmを実行します。例：

```bash
psalm --taint-analysis --dump-taint-graph=taints.dot
dot -Tsvg -o taints.svg taints.dot
```

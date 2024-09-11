# Psalmについて

Psalmは、プログラムを深く掘り下げ、できるだけ多くの型関連のバグを見つけようとする静的解析ツールです。

他の類似ツールよりも進んでいる機能がいくつかあります：

- **混合型の警告**<br />
  Psalmが式の型を推論できない場合、`mixed`というプレースホルダー型を使用します。`mixed`型は時にバグを隠してしまうことがあるため、これらを追跡することで多くの一般的な落とし穴を避けるのに役立ちます。

- **インテリジェントなロジックチェック**<br />
  Psalmはコードに関する論理的な主張を追跡するため、`if ($a && $a) {}`と`if ($a && !$a) {}`はどちらも問題として扱われます。また、以前のコードパスで行われた論理的な主張も追跡するため、`if ($a) {} elseif ($a) {}`のような問題を防ぎます。

- **プロパティ初期化チェック**<br />
  Psalmは、コンストラクタが呼び出された後、与えられたオブジェクトのすべてのプロパティに値が設定されているかをチェックします。

- **汚染分析**<br />
  Psalmは[コード内のセキュリティ脆弱性を検出](https://psalm.dev/articles/detect-security-vulnerabilities-with-psalm)できます。

- **言語サーバー**<br />
  Psalmには[さまざまなIDEと互換性のある](https://psalm.dev/docs/running_psalm/language_server/)言語サーバーがあります。

- **自動修正**<br />
  Psalmは[検出した多くの問題を自動的に修正](https://psalm.dev/docs/manipulating_code/fixing/)できます。

- **自動リファクタリング**<br />
  Psalmはコマンドラインから[簡単なリファクタリングを実行](https://psalm.dev/docs/manipulating_code/refactoring/)することもできます。

## 出力例

`implode_strings.php`というファイルがある場合：

```php
<?php
$a = ['foo', 'bar'];
echo implode($a, ' ');
```

```bash
> ./vendor/bin/psalm implode_strings.php
ERROR: InvalidArgument - somefile.php:3:14 - Argument 1 of implode expects `string`, `array` provided (see https://psalm.dev/004)
```

## インスピレーション

Psalmには主に2つのインスピレーションがあります：

- Etsyの[Phan](https://github.com/etsy/phan)。これはnikicの[php-ast](https://github.com/nikic/php-ast)拡張を使用して抽象構文木を作成します。
- Facebookの[Hack](http://hacklang.org/)。これはPHPに似た言語で、多くの高度な型付け機能をネイティブにサポートしているため、docblockが不要です。

## 目次

- Psalmの実行：
    - [インストール](running_psalm/installation.md)
    - [設定](running_psalm/configuration.md)
    - プラグイン
        - [プラグインの使用](running_psalm/plugins/using_plugins.md)
        - [プラグインの作成](running_psalm/plugins/authoring_plugins.md)
        - [Psalmが型を表現する方法](running_psalm/plugins/plugins_type_system.md)
    - [コマンドライン使用法](running_psalm/command_line_usage.md)
    - [IDEサポート](running_psalm/language_server.md)
    - エラー処理：
        - [コードの問題への対処](running_psalm/dealing_with_code_issues.md)
        - [問題の種類](running_psalm/issues.md)
    - [PHP以外のファイルのチェック](running_psalm/checking_non_php_files.md)
- コードへの注釈：
    - [Psalmでの型付け](annotating_code/typing_in_psalm.md)
    - [サポートされているアノテーション](annotating_code/supported_annotations.md)
    - [テンプレートアノテーション](annotating_code/templated_annotations.md)
- コードの操作：
    - [コードの修正](manipulating_code/fixing.md)
    - [コードのリファクタリング](manipulating_code/refactoring.md)

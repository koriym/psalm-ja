# インスタレーション

Psalm の最新版には PHP &gt;= 7.4 と[Composer](https://getcomposer.org/) が必要です。

```bash
composer require --dev vimeo/psalm
```

設定ファイルを生成する

```bash
./vendor/bin/psalm --init
```

Psalmはあなたのプロジェクトをスキャンし、コードベースに適した[error level](error_levels.md) 。

それからPsalmを実行する：

```bash
./vendor/bin/psalm
```

Psalmはおそらく多くの問題を見つけるだろう。[Dealing with code issues](dealing_with_code_issues.md) で、それらに対処する方法を見つけよう。

## プラグインのインストール

Psalmは、様々なライブラリのソースコードやdocblockから、そのライブラリが使用する型を割り出すことができますが、Psalmプラグインによって提供されるカスタムメイドの型を使用すると、さらにうまく動作します。

[list of existing plugins on Packagist](https://packagist.org/?type=psalm-plugin) をチェックしてください。`composer require --dev <plugin/package> && vendor/bin/psalm-plugin enable <plugin/package>`

プラグインについて詳しくは[Using Plugins chapter](plugins/using_plugins.md) をご覧ください。

## Pharを使う

プロジェクトがPsalmの依存パッケージと衝突することがあります。そのような場合は、Phar (自己完結型の PHP 実行ファイル) を使うと便利です。

Phar は Github からダウンロードできます：

```bash
wget https://github.com/vimeo/psalm/releases/latest/download/psalm.phar
chmod +x psalm.phar
./psalm.phar --version
```

また、Composerを使ってPharをインストールすることもできる：

```bash
composer require --dev psalm/phar
```

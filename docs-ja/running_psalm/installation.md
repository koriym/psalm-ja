# インストール

Psalmの最新バージョンには、PHP >= 7.4と[Composer](https://getcomposer.org/)が必要です。

```bash
composer require --dev vimeo/psalm
```

設定ファイルを生成します：

```bash
./vendor/bin/psalm --init
```

Psalmはプロジェクトをスキャンし、コードベースに適切な[エラーレベル](error_levels.md)を決定します。

そして、Psalmを実行します：

```bash
./vendor/bin/psalm
```

Psalmはおそらくいくつかの問題を見つけるでしょう - それらの対処方法については[コードの問題への対処](dealing_with_code_issues.md)を参照してください。

## プラグインのインストール

Psalmは様々なライブラリで使用される型をそのソースコードとdocblockに基づいて把握できますが、Psalmプラグインによって提供されるカスタムメイドの型を使用するとさらに効果的に動作します。

[Packagistにある既存のプラグインのリスト](https://packagist.org/?type=psalm-plugin)をチェックしてください。`composer require --dev <plugin/package> && vendor/bin/psalm-plugin enable <plugin/package>`でインストールできます。

プラグインについての詳細は[プラグインの使用の章](plugins/using_plugins.md)を参照してください。

## Pharの使用

プロジェクトがPsalmの依存関係の1つ以上と競合する場合があります。その場合、Phar（自己完結型のPHP実行可能ファイル）が役立つかもしれません。

PharはGithubからダウンロードできます：

```bash
wget https://github.com/vimeo/psalm/releases/latest/download/psalm.phar
chmod +x psalm.phar
./psalm.phar --version
```

あるいは、Composerを使用してPharをインストールすることもできます：

```bash
composer require --dev psalm/phar
```

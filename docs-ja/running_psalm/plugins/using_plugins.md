# プラグインの使用

Psalmは、プラグインを通じて拡張し、ドメイン固有の問題を見つけて修正することができます。

## Composerベースのプラグインの使用

Psalmプラグインはcomposerパッケージとして配布されています。

### プラグインの発見

[Psalm自身のウェブサイト](https://psalm.dev/plugins)や[Packagist](https://packagist.org/?type=psalm-plugin)でプラグインのリストを見つけることができます。あるいは、CLIで`composer search -t psalm-plugin '.'`とタイプしてリストを取得することもできます。

### プラグインのインストール

`composer require --dev <plugin-vendor/plugin-package>`

### 既知のプラグインの管理

インストールしたら、`psalm-plugin`ツールを使用して、利用可能なプラグインと有効なプラグインの有効化、無効化、表示を行います。

プラグインを有効にするには、`vendor/bin/psalm-plugin enable plugin-vendor/plugin-package`を実行します。

プラグインを無効にするには、`vendor/bin/psalm-plugin disable plugin-vendor/plugin-package`を実行します。

`vendor/bin/psalm-plugin show`は、すべてのローカルプラグイン（有効と無効の両方）のリストを表示します。

## 独自のプラグインの使用

お気に入りのフレームワーク/ライブラリ用のプラグインがまだありませんか？作成しましょう！リポジトリをフォークし、いくつかのdocblockを調整し、パッケージをPackagistに公開するだけで簡単です。

[プラグインの作成](authoring_plugins.md)の章を参照して、始めてください。

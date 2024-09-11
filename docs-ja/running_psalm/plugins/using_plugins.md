# プラグインの使用

Psalmは、ドメイン固有の問題を発見し修正するために、プラグインによって拡張することができる。

## Composerベースのプラグインを使う

Psalmのプラグインはcomposerパッケージとして配布されています。

### プラグインの発見

プラグインの一覧は[Psalm’s own website](https://psalm.dev/plugins) と[also on Packagist](https://packagist.org/?type=psalm-plugin) にあります。また、CLIで次のように入力しても一覧を取得できます。`composer search -t psalm-plugin '.'`

### プラグインのインストール

`composer require --dev <plugin-vendor/plugin-package>`

### 既知のプラグインの管理

インストールが完了したら、`psalm-plugin` ツールを使用して、利用可能なプラグインと有効なプラグインを有効化、無効化、表示します。

プラグインを有効にするには、`vendor/bin/psalm-plugin enable plugin-vendor/plugin-package`.

プラグインを無効にするには、`vendor/bin/psalm-plugin disable plugin-vendor/plugin-package` を実行する。

`vendor/bin/psalm-plugin show` を実行すると、すべてのローカル・プラグイン（有効・無効）のリストが表示されます。

## 独自のプラグインを使う

お気に入りのフレームワーク/ライブラリのプラグインがまだありませんか？作ってみましょう！リポジトリをフォークしてdocblockをいじり、Packagistにパッケージを公開するだけです。

[Authoring Plugins](authoring_plugins.md) の章を参考にしてください。

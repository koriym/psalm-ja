# プラグインの使用

Psalmは、プラグインを通じて拡張し、ドメイン固有の問題を発見・修正することができます。

## Composerベースのプラグインの使用

Psalmプラグインは、Composerパッケージとして配布されています。

### プラグインの探索

プラグインのリストは以下の場所で見つけることができます：

- [Psalmの公式ウェブサイト](https://psalm.dev/plugins)
- [Packagist](https://packagist.org/?type=psalm-plugin)

また、CLIで以下のコマンドを実行することでも一覧を取得できます：

```
composer search -t psalm-plugin '.'
```

### プラグインのインストール

以下のコマンドでプラグインをインストールします：

```
composer require --dev <plugin-vendor/plugin-package>
```

### プラグインの管理

インストール後は、`psalm-plugin`ツールを使用してプラグインの有効化、無効化、および利用可能なプラグインと有効なプラグインの表示を行うことができます。

プラグインを有効にするには：

```
vendor/bin/psalm-plugin enable plugin-vendor/plugin-package
```

プラグインを無効にするには：

```
vendor/bin/psalm-plugin disable plugin-vendor/plugin-package
```

すべてのローカルプラグイン（有効および無効）のリストを表示するには：

```
vendor/bin/psalm-plugin show
```

## 独自のプラグインの使用

お気に入りのフレームワークやライブラリ用のプラグインがまだない場合は、自分で作成することができます。リポジトリをフォークし、docblockを調整し、パッケージをPackagistに公開するだけで簡単に作成できます。

プラグインの作成を始めるには、[プラグインの作成](authoring_plugins.md)の章を参照してください。

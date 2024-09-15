# Psalmプラグインの作成

## クイックスタート

### テンプレートリポジトリの使用
1. Githubの[プラグインテンプレートリポジトリ](https://github.com/weirdan/psalm-plugin-skeleton)にアクセスします。
2. ログインして`Use this template`ボタンをクリックします。

### スケルトンプロジェクトの使用
1. 以下のコマンドを実行して、新しいプラグインプロジェクトを素早くブートストラップします：
   ```
   composer create-project weirdan/psalm-plugin-skeleton:dev-master your-plugin-name
   ```
2. `your-plugin-name`フォルダに新しいプロジェクトが作成されます。
3. `composer.json`、`Plugin.php`、`tests`フォルダの名前空間を適切に調整してください。

## スタブファイル
スタブファイルは、上流のソースファイルに直接Psalmの拡張docblockを追加できない場合に、サードパーティの型情報を上書きする方法を提供します。

- 慣例により、スタブファイルは`.phpstub`拡張子を持ちます。
- これにより、IDEが実際のPHPコードとして扱うのを避けられます。

### スタブの生成
1. 型を調整したいライブラリをdev-requireします：
   ```
   composer require --dev cakephp/chronos
   ```
2. スタブを生成します：
   ```
   vendor/bin/psalm --generate-stubs=stubs/chronos.phpstub
   ```
3. 生成されたファイルを開き、不要な部分を削除します。
4. docblockを調整して、より正確な型を提供します。

### スタブファイルの登録
- スケルトン/テンプレートプロジェクトには、`stubs`ディレクトリからすべての`.phpstub`ファイルを登録するコードが含まれています。
- スタブファイルを手動で登録するには、`Psalm\Plugin\RegistrationInterface::addStubFile()`を使用します。

## カスタムスキャナーとアナライザーの登録
XMLの設定ノード`<fileExtensions>`に加えて、プラグインは特定のファイル拡張子に対して独自のカスタムスキャナーとアナライザーの実装を登録できます。例：

- `Psalm\Plugin\FileExtensionsInterface::addFileTypeScanner('html', CustomFileScanner::class)`
- `Psalm\Plugin\FileExtensionsInterface::addFileTypeAnalyzer('html', CustomFileAnalyzer::class)`

## Packagistでのプラグインの公開
packagist.orgの「Publishing Packages」セクションの指示に従ってください。

## 高度なトピック

### ゼロから始める
Composerベースのプラグインは、以下の要件に適合するComposerパッケージです：

1. `type`フィールドが`psalm-plugin`に設定されている
2. `composer.json`に`extra.psalm.pluginClass`サブキーがあり、Psalmランタイムにプラグインを登録するためのエントリポイントクラスを参照している
3. エントリポイントクラスが`Psalm\Plugin\PluginEntryPointInterface`を実装している

### Psalm API
プラグインは`Psalm\Plugin\EventHandler\*`インターフェースの1つ（または複数）を実装することができます。

```php
<?php
class SomePlugin implements \Psalm\Plugin\EventHandler\AfterStatementAnalysisInterface
{
}
```

`Psalm\Plugin\EventHandler\*`は、実装できる以下のインターフェースを提供しています：

- `AfterAnalysisInterface` - Psalmが分析を完了した後に呼び出されます。
- `AfterClassLikeAnalysisInterface` - Psalmが特定のクラスの分析を完了した後に呼び出されます。
- `AfterClassLikeExistenceCheckInterface` - Psalmがクラス、インターフェース、またはトレイトへの参照を分析した後に呼び出されます。
- `AfterClassLikeVisitInterface` - Psalmがクラスライクの解析された抽象構文木をクロールした後に呼び出されます。
- `AfterCodebasePopulatedInterface` - Psalmが必要なファイルをスキャンし、コードベースデータを投入した後に呼び出されます。
- `AfterEveryFunctionCallAnalysisInterface` - Psalmが任意の関数呼び出しを評価した後に呼び出されます。
- `AfterExpressionAnalysisInterface` - Psalmが式を評価した後に呼び出されます。
- `AfterFileAnalysisInterface` - Psalmがファイルを分析した後に呼び出されます。
- `AfterFunctionCallAnalysisInterface` - Psalmがプロジェクト自体で定義された関数への呼び出しを評価した後に呼び出されます。
- `AfterFunctionLikeAnalysisInterface` - Psalmが特定の関数ライクの分析を完了した後に呼び出されます。
- `AfterMethodCallAnalysisInterface` - Psalmがメソッド呼び出しを分析した後に呼び出されます。
- `BeforeStatementAnalysisInterface` - Psalmが文を評価する前に呼び出されます。
- `AfterStatementAnalysisInterface` - Psalmが文を評価した後に呼び出されます。
- `BeforeAddIssueInterface` - Psalmが内部の`IssueBuffer`にアイテムを追加する前に呼び出されます。
- `BeforeFileAnalysisInterface` - Psalmがファイルを分析する前に呼び出されます。

その他、多数のインターフェースが提供されています。詳細は原文を参照してください。

### プラグインの設定
プラグインをPsalmの実行時に確実に実行するには、[設定](../configuration.md)に追加してください（Composerベースのプラグインには不要）：

```xml
<plugins>
    <plugin filename="src/plugins/SomePlugin.php" />
</plugins>
```

絶対パスを指定することもできます：

```xml
<plugins>
    <plugin filename="/path/to/SomePlugin.php" />
</plugins>
```

### Xdebugの使用
Psalmは実行時に_Xdebug_を無効にしますが、プラグインを作成する際にコードをステップバイステップでデバッグする必要がある場合は、以下のようにPsalmを実行することで拡張機能を許可できます：

```console
$ PSALM_ALLOW_XDEBUG=1 path/to/psalm
```

## 型システム
Psalmが型をどのように扱うかを理解するには、[このガイド](plugins_type_system.md)を読んでください。

## カスタムプラグインの問題の処理
プラグインは独自の問題を発行する必要がある場合があります。この場合、`Psalm\Issue\PluginIssue`を拡張する問題を発行できます。

カスタムプラグインの問題を抑制するには：

- docblockでの抑制: 問題名を使用（例：`/** @psalm-suppress NoFloatAssignment */`）
- Psalmの設定での抑制:

```xml
<PluginIssue name="NoFloatAssignment" errorLevel="suppress" />
```

より複雑なルールも使用可能です：

```xml
<PluginIssue name="NoFloatAssignment">
    <errorLevel type="suppress">
        <directory name="tests" />
    </errorLevel>
</PluginIssue>
```

## ファイルベースのプラグインからComposerベースのバージョンへのアップグレード
1. スケルトンを使用して新しいプラグインプロジェクトを作成します。
2. ファイルベースのプラグインのクラス名を、プラグインエントリポイントの`__invoke()`メソッドに渡された`Psalm\Plugin\RegistrationInterface`インスタンスの`registerHooksFromClass()`メソッドに渡します。

[変換例](https://github.com/vimeo/psalm/tree/master/examples/plugins/composer-based/echo-checker/)を参照してください。

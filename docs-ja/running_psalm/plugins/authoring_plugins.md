# プラグインの作成

## クイックスタート

### テンプレートリポジトリの使用
Githubの[プラグインテンプレートリポジトリ](https://github.com/weirdan/psalm-plugin-skeleton)に行き、ログインして`Use this template`ボタンをクリックします。

### スケルトンプロジェクトの使用
`composer create-project weirdan/psalm-plugin-skeleton:dev-master your-plugin-name`を実行して、`your-plugin-name`フォルダに新しいプラグインプロジェクトを素早くブートストラップします。`composer.json`、`Plugin.php`、`tests`フォルダの名前空間を調整することを忘れないでください。

## スタブファイル
スタブファイルは、上流のソースファイルに直接Psalmの拡張docblockを追加できない場合に、サードパーティの型情報を上書きする方法を提供します。
慣例により、スタブファイルは`.phpstub`拡張子を持ち、IDEが実際のPHPコードとして扱うのを避けます。

## スタブの生成
型を調整したいライブラリをdev-requireします。例：
```
composer require --dev cakephp/chronos
```
次に、スタブを生成します
```
vendor/bin/psalm --generate-stubs=stubs/chronos.phpstub
```
生成されたファイルを開き、スタブ化しているライブラリに関係ないものをすべて削除します。docblockを調整して、より正確な型を提供します。

## スタブファイルの登録
スケルトン/テンプレートプロジェクトには、`stubs`ディレクトリからすべての`.phpstub`ファイルを登録するコードが含まれています。
スタブファイルを手動で登録するには、`Psalm\Plugin\RegistrationInterface::addStubFile()`を使用します。

## カスタムスキャナーとアナライザーの登録
XMLの設定ノード`<fileExtensions>`に加えて、プラグインは特定のファイル拡張子に対して独自のカスタムスキャナーとアナライザーの実装を登録できます。例：
* `Psalm\Plugin\FileExtensionsInterface::addFileTypeScanner('html', CustomFileScanner::class)`
* `Psalm\Plugin\FileExtensionsInterface::addFileTypeAnalyzer('html', CustomFileAnalyzer::class)`

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
class SomePlugin implements \Psalm\Plugin\EventHandler\AfterStatementAnalysisInterface{}
```

`Psalm\Plugin\EventHandler\*`は、実装できる以下のインターフェースを提供しています：

- `AfterAnalysisInterface` - Psalmが分析を完了した後に呼び出されます。分析結果で何かをしたい場合にこのフックを使用します。
- `AfterClassLikeAnalysisInterface` - Psalmが特定のクラスの分析を完了した後に呼び出されます。
- `AfterClassLikeExistenceCheckInterface` - Psalmがクラス、インターフェース、またはトレイトへの参照を分析した後に呼び出されます。
- `AfterClassLikeVisitInterface` - Psalmがクラスライク（クラス、インターフェース、トレイト）の解析された抽象構文木をクロールした後に呼び出されます。キャッシングのため、ASTは最初にPsalmがファイルを見たときにクロールされ、ファイルが変更された場合、キャッシュがクリアされた場合、または`--no-cache`/`--no-reflection-cache`でキャッシュを無効にしている場合にのみ再クロールされます。Psalmが分析を開始する前にクラスに関する情報を収集または変更したい場合に使用します。
- `AfterCodebasePopulatedInterface` - Psalmが必要なファイルをスキャンし、コードベースデータを投入した後に呼び出されます。
- `AfterEveryFunctionCallAnalysisInterface` - Psalmが任意の関数呼び出しを評価した後に呼び出されます。呼び出しをさらに影響を与えることはできません。
- `AfterExpressionAnalysisInterface` - Psalmが式を評価した後に呼び出されます。
- `AfterFileAnalysisInterface` - Psalmがファイルを分析した後に呼び出されます。
- `AfterFunctionCallAnalysisInterface` - Psalmがプロジェクト自体で定義された任意の関数への呼び出しを評価した後に呼び出されます。戻り型を変更したり、呼び出しの修正を行ったりできます。
- `AfterFunctionLikeAnalysisInterface` - Psalmが特定の関数ライクの分析を完了した後に呼び出されます。
- `AfterMethodCallAnalysisInterface` - Psalmがメソッド呼び出しを分析した後に呼び出されます。
- `BeforeStatementAnalysisInterface` - Psalmが文を評価する前に呼び出されます。
- `AfterStatementAnalysisInterface` - Psalmが文を評価した後に呼び出されます。
- `BeforeAddIssueInterface` - Psalmが内部の`IssueBuffer`にアイテムを追加する前に呼び出され、コードの問題を個別に処理できます。
- `BeforeFileAnalysisInterface` - Psalmがファイルを分析する前に呼び出されます。
- `FunctionExistenceProviderInterface` - 1つ以上の関数に対してPsalmのビルトイン関数存在チェックを上書きするために使用できます。
- `FunctionParamsProviderInterface.php` - 1つ以上の関数に対してPsalmのビルトイン関数パラメータルックアップを上書きするために使用できます。
- `FunctionReturnTypeProviderInterface` - 1つ以上の関数に対してPsalmのビルトイン関数戻り型ルックアップを上書きするために使用できます。
- `MethodExistenceProviderInterface` - 1つ以上のクラスに対してPsalmのビルトインメソッド存在チェックを上書きするために使用できます。
- `MethodParamsProviderInterface` - 1つ以上のクラスに対してPsalmのビルトインメソッドパラメータルックアップを上書きするために使用できます。
- `MethodReturnTypeProviderInterface` - 1つ以上のクラスに対してPsalmのビルトインメソッド戻り型ルックアップを上書きするために使用できます。
- `MethodVisibilityProviderInterface` - 1つ以上のクラスに対してPsalmのビルトインメソッド可視性チェックを上書きするために使用できます。
- `PropertyExistenceProviderInterface` - 1つ以上のクラスに対してPsalmのビルトインプロパティ存在チェックを上書きするために使用できます。
- `PropertyTypeProviderInterface` - 1つ以上のクラスに対してPsalmのビルトインプロパティ型ルックアップを上書きするために使用できます。
- `PropertyVisibilityProviderInterface` - 1つ以上のクラスに対してPsalmのビルトインプロパティ可視性チェックを上書きするために使用できます。
- `DynamicFunctionStorageProviderInterface` - 1つ以上の関数のシグネチャを上書きするために使用できます。これは、可変長引数リストを定義したり、入力引数から戻り型を推論したり、関数テンプレートを定義したりできることを意味します。また、`Psalm\Plugin\DynamicFunctionStorage`をチェックして、関数の定義を変更するためのAPIを確認してください。

以下にいくつかのサンプルプラグインがあります：
- [StringChecker](https://github.com/vimeo/psalm/blob/master/examples/plugins/StringChecker.php) - 文字列内のクラス参照をチェックします
- [PreventFloatAssignmentChecker](https://github.com/vimeo/psalm/blob/master/examples/plugins/PreventFloatAssignmentChecker.php) - floatへの代入を防ぎます
- [FunctionCasingChecker](https://github.com/vimeo/psalm/blob/master/examples/plugins/FunctionCasingChecker.php) - 関数とメソッドが正しく大文字小文字が使われているかチェックします

プラグインをPsalmの実行時に確実に実行するには、[設定](../configuration.md)に追加してください（Composerベースのプラグインには不要）：

```xml
    <plugins>
        <plugin filename="src/plugins/SomePlugin.php" />
    </plugins>
```

プラグインへの絶対パスを指定することもできます：

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
[このガイドを読んで](plugins_type_system.md)、Psalmが型をどのように扱うかを理解してください。

## カスタムプラグインの問題の処理
プラグインは時々、独自の問題（つまり、[既存の問題](../issues.md)の1つを発行しない）を発行する必要がある場合があります。この場合、`Psalm\Issue\PluginIssue`を拡張する問題を発行できます。

カスタムプラグインの問題をdocblockで抑制するには、単にその問題名を使用できます（例：`/** @psalm-suppress NoFloatAssignment */`）。ただし、[Psalmの設定で抑制する](../dealing_with_code_issues.md#config-suppression)には、以下のパターンを使用する必要があります：

```xml
<PluginIssue name="NoFloatAssignment" errorLevel="suppress" />
```

他の問題タイプと同様に、`<issueHandler />`要素でより複雑なルールを使用することもできます。例：

```xml
<PluginIssue name="NoFloatAssignment">
    <errorLevel type="suppress">
        <directory name="tests" />
    </errorLevel>
</PluginIssue>
```

## ファイルベースのプラグインからComposerベースのバージョンへのアップグレード
スケルトンを使用して新しいプラグインプロジェクトを作成し、ファイルベースのプラグインのクラス名を、プラグインエントリポイントの`__invoke()`メソッドに渡された`Psalm\Plugin\RegistrationInterface`インスタンスの`registerHooksFromClass()`メソッドに渡します。[変換例](https://github.com/vimeo/psalm/tree/master/examples/plugins/composer-based/echo-checker/)を参照してください。

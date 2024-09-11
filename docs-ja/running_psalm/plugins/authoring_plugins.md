# オーサリングプラグイン

## クイックスタート

### テンプレートリポジトリを使う

Githubの[plugin template repository](https://github.com/weirdan/psalm-plugin-skeleton) 、ログインして`Use this template` 。

### スケルトン・プロジェクトを使う

`composer create-project weirdan/psalm-plugin-skeleton:dev-master your-plugin-name` を実行して、`your-plugin-name` フォルダーに新しいプラグイン・プロジェクトを素早くブートストラップします。`composer.json`,`Plugin.php`,`tests` フォルダの名前空間を調整してください。


## スタブファイル

スタブファイルは、Psalm の拡張 docblock を上流のソースファイルに直接追加できない場合に、 サードパーティの型情報を上書きするためのものです。`.phpstub` IDEがスタブファイルを実際のphpコードとして扱わないようにするためです。

## スタブの生成

型を調整したいライブラリを Dev-require してください。
```
composer require --dev cakephp/chronos
```
次にスタブを生成する
```
vendor/bin/psalm --generate-stubs=stubs/chronos.phpstub
```
生成されたファイルを開き、スタブ対象のライブラリに関係ないものをすべて削除する。より正確な型を提供するためにdocblocksを微調整する。

## スタブファイルの登録

スケルトン/テンプレート・プロジェクトには、`stubs` ディレクトリから`.phpstub` ファイルをすべて登録するコードが含まれています。

スタブファイルを手動で登録するには、`Psalm\Plugin\RegistrationInterface::addStubFile()`.

## カスタムスキャナーとアナライザーの登録

XML設定ノードに加えて `<fileExtensions>`プラグインは、特定のファイル拡張子に対して独自のカスタムスキャナーとアナライザーの実装を登録することができます。

*`Psalm\Plugin\FileExtensionsInterface::addFileTypeScanner('html', CustomFileScanner::class)` *`Psalm\Plugin\FileExtensionsInterface::addFileTypeAnalyzer('html', CustomFileAnalyzer::class)`

## Packagistでプラグインを公開する

packagist.orgの「パッケージの公開」セクションの指示に従ってください。

## 高度なトピック

### ゼロから始める

Composer ベースのプラグインは、これらの要件に準拠した composer パッケージです：

1.`type` フィールドが`psalm-plugin` に設定されている。`composer.json` に`extra.psalm.pluginClass` サブキーがあり、プラグインを Psalm ランタイムに登録するために呼び出されるエントリーポイントクラスを参照している。3.エントリーポイント・クラスは`Psalm\Plugin\PluginEntryPointInterface`

### Psalm API

プラグインは、`Psalm\Plugin\EventHandler\*` インターフェイスの1つ（または複数）を実装することができます。

```php
<?php
class SomePlugin implements \Psalm\Plugin\EventHandler\AfterStatementAnalysisInterface
{
}
```

`Psalm\Plugin\EventHandler\*` には実装可能な以下のインターフェイスがあります：

-`AfterAnalysisInterface` - Psalmが分析を完了した後に呼び出される。このフックは、解析結果を使って何かをしたい場合に使います。
-`AfterClassLikeAnalysisInterface` - Psalm が指定されたクラスの解析を完了した後に呼び出されます。
-`AfterClassLikeExistenceCheckInterface` - Psalm がクラス、インターフェイス、trait への参照を解析した後に呼び出されます。
-`AfterClassLikeVisitInterface` - Psalm が解析された抽象構文木をクロールして、クラスのようなもの（クラス、インターフェイス、 trait）を探した後に呼び出されます。キャッシュにより、AST は Psalm が最初にファイルを見たときにクロールされ、ファイルが変更されたとき、キャッシュがクリアされたとき、または`--no-cache`/`--no-reflection-cache` でキャッシュを無効にしたときにのみ再クロールされます。Psalm が解析を開始する前に、クラスに関する情報を収集したり変更したりしたい場合に使用します。
-`AfterCodebasePopulatedInterface` - Psalm が必要なファイルをスキャンし、コードベース・データを入力した後に呼び出されます。
-`AfterEveryFunctionCallAnalysisInterface` - Psalm が関数呼び出しを評価した後に呼び出されます。それ以上呼び出しに影響を与えることはできません。
-`AfterExpressionAnalysisInterface` - Psalmが式を評価した後に呼び出される。
-`AfterFileAnalysisInterface` - Psalmがファイルを解析した後に呼び出されます。
-`AfterFunctionCallAnalysisInterface` - Psalmがプロジェクト内で定義された関数を評価した後に呼び出されます。戻り値の型を変更したり、呼び出しを修正したりすることができます。
-`AfterFunctionLikeAnalysisInterface` - Psalmが指定された関数の解析を完了した後に呼び出されます。
-`AfterMethodCallAnalysisInterface` - Psalmがメソッド呼び出しを解析した後に呼び出されます。
-`BeforeStatementAnalysisInterface` - Psalmが文を評価する前に呼ばれる。
-`AfterStatementAnalysisInterface` - Psalmが文を評価した後に呼ばれる。
-`BeforeAddIssueInterface` - Psalmが内部`IssueBuffer` に項目を追加する前に呼び出される。
-`BeforeFileAnalysisInterface` - Psalmがファイルを解析する前に呼び出される。
-`FunctionExistenceProviderInterface` - Psalmの組み込み関数の存在チェックをオーバーライドするために使用できます。
-`FunctionParamsProviderInterface.php` - Psalmの組み込み関数のパラメータ検索を1つ以上の関数に対してオーバーライドするために使用できます。
-`FunctionReturnTypeProviderInterface` - Psalmの組み込み関数の戻り値の型検索を1つ以上の関数でオーバーライドするために使用できます。
-`MethodExistenceProviderInterface` - Psalm の組み込みメソッドの存在チェックを、1 つ以上のクラスに対してオーバーライドするために使用できます。
-`MethodParamsProviderInterface` - Psalm 組み込みのメソッド・パラメータ検索を、1 つ以上のクラスに対してオーバーライドするために使用できます。
-`MethodReturnTypeProviderInterface` - Psalm の組み込みメソッドの戻り値の型検索を、1 つ以上のクラスに対してオーバーライドするために使用できます。
-`MethodVisibilityProviderInterface` - Psalm の組み込みメソッドの可視性チェックを、1 つ以上のクラスに対してオーバーライドするために使用できます。
-`PropertyExistenceProviderInterface` - Psalm の組み込みプロパティの存在チェックを、1 つ以上のクラスに対してオーバーライドするために使用できます。
-`PropertyTypeProviderInterface` - Psalm の組み込みプロパティの型検索を、1 つ以上のクラスに対してオーバーライドするために使用できます。
-`PropertyVisibilityProviderInterface` - Psalm の組み込みプロパティの可視性チェックを、1 つ以上のクラスに対してオーバーライドするために使用できます。
-`DynamicFunctionStorageProviderInterface` - 1 つ以上の関数のシグニチャをオーバーライドするために使用できます。つまり、多様なパラメータリストを定義することができます。入力引数から戻り値の型を推測します。関数テンプレートの定義。また、`Psalm\Plugin\DynamicFunctionStorage` 、関数の定義を変更するためにどのようなAPIが必要かを確認してください。

以下はプラグインの例です：
 -[StringChecker](https://github.com/vimeo/psalm/blob/master/examples/plugins/StringChecker.php) - 文字列中のクラス参照をチェックする。
 -[PreventFloatAssignmentChecker](https://github.com/vimeo/psalm/blob/master/examples/plugins/PreventFloatAssignmentChecker.php) - 浮動小数点への代入を防ぐ
 -[FunctionCasingChecker](https://github.com/vimeo/psalm/blob/master/examples/plugins/FunctionCasingChecker.php) - 関数とメソッドが正しくケース分けされているかチェックする。

Psalmが実行するときにプラグインが実行されるようにするには、[config](../configuration.md) に追加してください（composerベースのプラグインには必要ありません）：
```xml
    <plugins>         <plugin filename="src/plugins/SomePlugin.php" />     </plugins>
```

プラグインへの絶対パスを指定することもできます：
```xml
    <plugins>         <plugin filename="/path/to/SomePlugin.php" />     </plugins>
```

### Xdebugを使用する

Psalmは実行時に_Xdebug_を無効にするので、プラグインを作成する際にコードを段階的にデバッグする必要がある場合は、以下のようにPsalmを実行することで拡張機能を許可することができます：

```console
$ PSALM_ALLOW_XDEBUG=1 path/to/psalm
```

## タイプシステム

[reading this guide](plugins_type_system.md) によって、Psalm がどのように型を扱うかを理解する。

## カスタムプラグインの問題を扱う

プラグインは独自のissueを発行する必要がある場合があります(つまり、[existing issues](../issues.md) のissueを発行しない)。その場合、`Psalm\Issue\PluginIssue` を拡張したissueを発行することができます。

カスタムプラグインのissueをdocblocksで抑制するには、そのissue名を使用するだけです(例:`/** @psalm-suppress NoFloatAssignment */`, しかし[suppress it in Psalm’s config](../dealing_with_code_issues.md#config-suppression) にはパターンを使用しなければなりません)：

```xml
<PluginIssue name="NoFloatAssignment" errorLevel="suppress" />
```

また `<issueHandler />`要素でより複雑なルールを使用することもできます。

```xml
<PluginIssue name="NoFloatAssignment">     <errorLevel type="suppress">         <directory name="tests" />     </errorLevel> </PluginIssue>
```

## ファイルベースのプラグインをコンポーザーベースにアップグレードする

スケルトンを使って新しいプラグインプロジェクトを作成し、ファイルベースのプラグインのクラス名を、プラグインのエントリーポイントに渡された インスタンスの`registerHooksFromClass()`
`Psalm\Plugin\RegistrationInterface` メソッドに渡します。`__invoke()`
メソッドに渡します。[conversion example](https://github.com/vimeo/psalm/tree/master/examples/plugins/composer-based/echo-checker/) を参照してください。

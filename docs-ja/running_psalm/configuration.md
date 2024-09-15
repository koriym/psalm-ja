# 設定

PsalmはXML設定ファイル（デフォルトでは`psalm.xml`）を使用します。最小限の例は次のようになります：

```xml
<?xml version="1.0"?>
<psalm>
    <projectFiles>
        <directory name="src" />
    </projectFiles>
</psalm>
```

設定ファイルは、[XInclude](https://www.w3.org/TR/xinclude/)タグを使用して複数のファイルに分割できます（前の例を参照）：

#### psalm.xml
```xml
<?xml version="1.0"?>
<psalm
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xmlns="https://getpsalm.org/schema/config"
        xsi:schemaLocation="https://getpsalm.org/schema/config vendor/vimeo/psalm/config.xsd"
        xmlns:xi="http://www.w3.org/2001/XInclude">
    <xi:include href="files.xml"/>
</psalm>
```

#### files.xml
```xml
<?xml version="1.0" encoding="UTF-8"?>
<projectFiles xmlns="https://getpsalm.org/schema/config">
    <file name="Bar.php" />
    <file name="Bat.php" />
</projectFiles>
```

## オプションの &lt;psalm /&gt; 属性

### コーディングスタイル

#### errorLevel
```xml
<psalm
  errorLevel="[int]"/>
```
これはPsalmの[エラー検出レベル](error_levels.md)に対応します。

#### reportMixedIssues
```xml
<psalm
  reportMixedIssues="[bool]"/>
```
これを`"false"`に設定すると、Psalmの出力で`Mixed`型の問題がすべて非表示になります。指定されていない場合、`errorLevel`が3以上の場合はデフォルトで`"false"`、エラーレベルが1または2の場合は`"true"`になります。

#### totallyTyped
（非推奨）この設定は`reportMixedIssues`に置き換えられました。`errorLevel`が1の場合に自動的に有効になります。

#### resolveFromConfigFile
```xml
<psalm
  resolveFromConfigFile="[bool]"/>
```
これが有効な場合、設定ファイルで言及された相対ディレクトリは、設定ファイルの位置を基準に解決されます。無効または存在しない場合、Psalmプロセスの作業ディレクトリを基準に解決されます。
新しいバージョンのPsalmは、設定ファイルを生成する際にこのオプションを有効にします。古いバージョンではこれを含めませんでした。

#### useDocblockTypes
```xml
<psalm
  useDocblockTypes="[bool]">
```
docblockで定義された型を使用するかどうか。デフォルトは`true`です。

#### useDocblockPropertyTypes
```xml
<psalm
  useDocblockPropertyTypes="[bool]">
```
すべてのdocblock型を使用しない場合でも、docblockプロパティ型を使用できます。デフォルトは`false`です（`useDocblockTypes`が`false`の場合のみ関連）。

#### docblockPropertyTypesSealProperties
```xml
<psalm
  docblockPropertyTypesSealProperties="[bool]">
```
クラスのdocblockで@propertyを使用することが@psalm-seal-propertiesを暗示するかどうか。デフォルトは`true`です。

#### usePhpDocMethodsWithoutMagicCall
```xml
<psalm
  usePhpDocMethodsWithoutMagicCall="[bool]">
```
通常、PHPDocの`@method`アノテーションは`__call`メソッドを持つクラスにのみ適用されます。これを`true`に設定すると、継承されたメソッドの戻り値の型をオーバーライドするために`@method`アノテーションを使用できます。デフォルトは`false`です。

#### usePhpDocPropertiesWithoutMagicCall
```xml
<psalm
  usePhpDocPropertiesWithoutMagicCall="[bool]">
```
通常、PHPDocの`@property`、`@property-read`、`@property-write`アノテーションは`__get`/`__set`メソッドを持つクラスにのみ適用されます。これを`true`に設定すると、プロパティの存在チェックと結果のプロパティ型をオーバーライドするために`@property`、`@property-read`、`@property-write`アノテーションを使用できます。デフォルトは`false`です。

#### disableVarParsing
```xml
<psalm
  disableVarParsing="[bool]"/>
```
プロパティ以外のすべての場所で`@var` PHPDocの解析を無効にします。これを`true`に設定すると、Psalm genericsと適切な型付けの統合以前に使用された古い`@var`アノテーションによる多くの誤検出を除去し、単一の真実の源の原則を強制できます。デフォルトは`false`です。

#### strictBinaryOperands
```xml
<psalm
  strictBinaryOperands="[bool]">
```
trueの場合、数値および文字列演算に厳密な型付けを強制します（https://github.com/vimeo/psalm/issues/24 を参照）。デフォルトは`false`です。

#### rememberPropertyAssignmentsAfterCall
```xml
<psalm
  rememberPropertyAssignmentsAfterCall="[bool]">
```
これを`false`に設定すると、関数呼び出しによって、Psalmが現在分析している関数のスコープ内のオブジェクトプロパティについて知っていたことを忘れさせます。これはHackの機能を複製しています。デフォルトは`true`です。

#### allowStringToStandInForClass
```xml
<psalm
  allowStringToStandInForClass="[bool]">
```
`true`の場合、文字列をクラスとして使用できます。つまり、`$some_string::someMethod()`が許可されます。`false`の場合、クラス定数文字列（`Foo\Bar::class`の形式）のみがクラスの代わりに使用でき、そうでない場合は`InvalidStringClass`問題が発生します。デフォルトは`false`です。

#### disableSuppressAll
```xml
<psalm
  disableSuppressAll="[bool]">
```
`true`の場合、`@psalm-suppress all`によるすべての問題のワイルドカード抑制を無効にします。デフォルトは`false`です。

#### memoizeMethodCallResults
```xml
<psalm
  memoizeMethodCallResults="[bool]">
```
`true`の場合、引数なしのメソッド呼び出しの結果は、特定のオブジェクトでそのメソッドを繰り返し呼び出す間で記憶されます。デフォルトは`false`です。

#### hoistConstants
```xml
<psalm
  hoistConstants="[bool]">
```
`true`の場合、ファイル内の関数で定義された定数は、その関数を呼び出すときだけでなく、そのファイルを要求するときに利用可能であると想定されます。デフォルトは`false`です（つまり、関数で定義された定数は、その関数が呼び出されたときにのみ使用可能になります）。

#### addParamDefaultToDocblockType
```xml
<psalm
  addParamDefaultToDocblockType="[bool]">
```
時々、パラメータのデフォルト値がdocblockの型と一致しないことがあります。デフォルトでは、Psalmは問題を発生させます。このフラグを`true`に設定すると、パラメータの型にデフォルト値を含めるように拡張します。デフォルトは`false`です。

#### checkForThrowsDocblock
```xml
<psalm
  checkForThrowsDocblock="[bool]">
```
`true`の場合、Psalmは開発者が特定の関数またはメソッドでスローされるすべての例外に対して`@throws`docblockを提供しているかどうかをチェックします。デフォルトは`false`です。

#### checkForThrowsInGlobalScope
```xml
<psalm
  checkForThrowsInGlobalScope="[bool]">
```
`true`の場合、Psalmは開発者がグローバルスコープですべての例外をキャッチしているかどうかをチェックします。デフォルトは`false`です。

#### #### ignoreInternalFunctionFalseReturn
```xml
<psalm
  ignoreInternalFunctionFalseReturn="[bool]">
```
`true`の場合、Psalmは内部関数（`preg_split`など）の戻り値から生じる可能性のあるfalseの問題を無視します。これらの関数は稀にfalseを返す可能性がありますが、通常は無視しても問題ありません。デフォルトは`false`です。

#### ignoreInternalFunctionNullReturn
```xml
<psalm
  ignoreInternalFunctionNullReturn="[bool]">
```
`true`の場合、Psalmは内部配列関数（`current`など）の戻り値から生じる可能性のあるnullの問題を無視します。これらの関数は稀にnullを返す可能性がありますが、通常は無視しても問題ありません。デフォルトは`false`です。

#### inferPropertyTypesFromConstructor
```xml
<psalm
  inferPropertyTypesFromConstructor="[bool]">
```
`true`の場合、Psalmは単純なコンストラクタで見られる代入からプロパティ型を推論します。デフォルトは`true`です。

#### findUnusedVariablesAndParams
```xml
<psalm
  findUnusedVariablesAndParams="[bool]">
```
`true`の場合、Psalmはすべての未使用の変数を見つけようとします。これは`--find-unused-variables`オプションを使用して実行するのと同等です。デフォルトは`false`です。

#### findUnusedCode
```xml
<psalm
  findUnusedCode="[bool]">
```
`true`の場合、Psalmはすべての未使用のコード（未使用の変数を含む）を見つけようとします。これは`--find-unused-code`オプションを使用して実行するのと同等です。デフォルトは`false`です。

#### findUnusedPsalmSuppress
```xml
<psalm
  findUnusedPsalmSuppress="[bool]">
```
`true`の場合、Psalmは使用されていないすべての`@psalm-suppress`アノテーションを報告します。これは`--find-unused-psalm-suppress`オプションを使用して実行するのと同等です。デフォルトは`false`です。

#### ensureArrayStringOffsetsExist
```xml
<psalm
  ensureArrayStringOffsetsExist="[bool]">
```
`true`の場合、Psalmは配列の明示的な文字列オフセット（例：`$arr['foo']`）を参照する際に、ユーザーが最初にその存在を確認（`isset`チェックまたはオブジェクトのような配列を介して）していない場合に警告します。デフォルトは`false`です。

#### ensureArrayIntOffsetsExist
```xml
<psalm
  ensureArrayIntOffsetsExist="[bool]">
```
`true`の場合、Psalmは配列の明示的な整数オフセット（例：`$arr[7]`）を参照する際に、ユーザーが最初にその存在を確認（`isset`チェックまたはオブジェクトのような配列を介して）していない場合に警告します。デフォルトは`false`です。

#### ensureOverrideAttribute
```xml
<psalm
  ensureOverrideAttribute="[bool]">
```
`true`の場合、Psalmは親のメソッドをオーバーライドするクラスおよびインターフェースのメソッドで、`Override`属性がない場合に報告します。デフォルトは`false`です。

#### phpVersion
```xml
<psalm
  phpVersion="[string]">
```
プロジェクトのチェックやフィックス時にPsalmが想定すべきPHPバージョンを設定します。この属性が設定されていない場合、Psalmは`composer.json`での宣言があればそれを使用します。宣言された`php`依存関係を満たす最も古いバージョンのPHPに対してチェックします。

これはコマンドラインで`--php-version=`フラグを使用してオーバーライドできます。コマンドラインフラグは`phpVersion`設定と`composer.json`から派生したバージョンの両方に対して最も高い優先順位を持ちます。

#### skipChecksOnUnresolvableIncludes
```xml
<psalm
  skipChecksOnUnresolvableIncludes="[bool]">
```
`true`の場合、Psalmは解決できない`include`または`require`に遭遇した後、クラス、変数、関数のチェックをスキップします。これにより、Psalmが知らない関数やクラスを参照するコードが許可されます。

デフォルトは`false`です。

#### sealAllMethods
```xml
<psalm
  sealAllMethods="[bool]">
```
`true`の場合、Psalmはすべてのクラスがシールドされたメソッドを持っているかのように扱います。つまり、マジックメソッド`__call`を実装する場合、各マジックメソッドに対して`@method`も追加する必要があります。デフォルトはfalseです。

#### sealAllProperties
```xml
<psalm
  sealAllProperties="[bool]">
```
`true`の場合、Psalmはすべてのクラスがシールドされたプロパティを持っているかのように扱います。つまり、Psalmは`@property`（または`@property-read`/`@property-write`）アノテーションのリストに含まれておらず、明示的に`property`として定義されていないプロパティの取得と設定を許可しません。デフォルトはfalseです。

#### runTaintAnalysis
```xml
<psalm
  runTaintAnalysis="[bool]">
```
`true`の場合、Psalmはコードベースで[汚染分析](../security_analysis/index.md)を実行します。この設定は、Psalmを`--taint-analysis`オプション付きで実行するのと同じです。

#### reportInfo
```xml
<psalm
  reportInfo="[bool]">
```
`false`の場合、Psalmは`errorLevel`よりも低いレベルの問題を`info`として扱いません（代わりに抑制されます）。これは大規模プロジェクトの分析時間を大幅に改善できます。ただし、この設定により、Psalmは抑制された問題の数を数えたり、修正を提案したりすることができなくなります。

#### allowNamedArgumentCalls
```xml
<psalm
  allowNamedArgumentCalls="[bool]">
```
`false`の場合、Psalmはコード内の`ParamNameMismatch`問題を報告しなくなります。これは、ライブラリのメソッドへの外部アクセスを防いだり、可変引数を使用する際に型を`list`に減らしたりするための個々の`@no-named-arguments`の使用に取って代わるものではありません。

#### triggerErrorExits
```xml
<psalm
  triggerErrorExits="[string]">
```
trigger_errorの動作を記述します。`always`は常に終了することを意味し、`never`は決して終了しないことを意味し、`default`は`E_USER_ERROR`の場合にのみ終了することを意味します。デフォルトは`default`です。

### Psalmの実行

#### autoloader
```xml
<psalm
  autoloader="[string]">
```
アプリケーションが1つ以上のカスタムオートローダーを登録し、かつ/または普遍的な定数/関数を宣言する場合、このオートローダースクリプトはスキャンが開始される前にPsalmによって実行されます。Psalmは常にデフォルトでcomposerのオートローダーを登録します。

#### throwExceptionOnError
```xml
<psalm
  throwExceptionOnError="[bool]">
```
テストで便利です。これにより、Psalmはエラーに遭遇したときに通常の例外をスローします。デフォルトは`false`です。

#### hideExternalErrors
```xml
<psalm
  hideExternalErrors="[bool]">
```
プロジェクトファイルで使用されているが、`<projectFiles>`に含まれていないファイルの問題を表示するかどうか。デフォルトは`false`です。

#### hideAllErrorsExceptPassedFiles
```xml
<psalm
  hideAllErrorsExceptPassedFiles="[bool]">
```
CLIで明示的に引数として渡されたファイルに対してのみ問題を報告するかどうか。これは、CLIで設定されていない場合、require/includeで読み込まれたファイルも報告しないことを意味します。単一または選択したファイルのエラーのみをチェックしたい場合に便利です。デフォルトは`false`です。

#### cacheDirectory
```xml
<psalm
  cacheDirectory="[string]">
```
Psalmのキャッシュデータを保存するディレクトリ - 指定する場合（まだ存在しない場合）、その親ディレクトリが既に存在している必要があります。そうでない場合、Psalmはエラーをスローします。

デフォルトは`$XDG_CACHE_HOME/psalm`です。`$XDG_CACHE_HOME`が設定されていないか空の場合、`$HOME/.cache/psalm`が使用されます。これが定義されていない場合は`sys_get_temp_dir() . '/psalm'`が使用されます。

#### allowFileIncludes
```xml
<psalm
  allowFileIncludes="[bool]">
```
PHPで`require`/`include`呼び出しを許可するかどうか。デフォルトは`true`です。

#### serializer
```xml
<psalm
  serializer="['igbinary'|'default']">
```
Psalmがデータのキャッシュに使用するシリアライザーをハードコードできます。デフォルトでは、Psalmはバージョンが2.0.5以上の場合は`ext-igbinary`を使用し、そうでない場合はPHPの組み込みシリアライザーを使用します。

#### compressor
```xml
<psalm
  compressor="['lz4'|'deflate'|'off']">
```
Psalmのキャッシュに使用する圧縮方法をハードコードできます。デフォルトでは、Psalmは有効な場合は`ext-zlib`のdeflateを使用します。

#### threads
```xml
<psalm
        threads="[int]">
```
Psalmが使用するスレッド数をハードコードできます（コマンドラインの`--threads`と同様）。この値はホストマシンからのスレッド検出の代わりに使用されますが、コマンドラインで`--threads`または`--debug`（スレッドを1に設定）を使用すると上書きされます。

#### maxStringLength
```xml
<psalm
  maxStringLength="1000">
```
この設定は、Psalm分析中にリテラル文字列型に変換される文字列リテラルの最大長を制御します。この値（デフォルトでは1000バイト）より長い文字列は、代わりに一般的な`non-empty-string`型に変換されます。この設定を変更すると望ましくない副作用が発生する可能性があり、それらの副作用はバグとは見なされないことに注意してください。

#### maxShapedArraySize
```xml
<psalm
  maxShapedArraySize="100">
```
この設定は、Psalm分析中に形状付き`array{key1: "value", key2: T}`型に変換される形状付き配列の最大サイズを制御します。この値（デフォルトでは100）より大きい配列は、代わりに一般的な`non-empty-array`型に変換されます。この設定を変更すると望ましくない副作用が発生する可能性があり、それらの副作用はバグとは見なされないことに注意してください。

#### restrictReturnTypes
```xml
<psalm
  restrictReturnTypes="true">
```
宣言された戻り値の型が推論された戻り値の型ほど厳密でない場合に`LessSpecificReturnType`を発行します。

このコード：
```php
function getOne(): int // 宣言された型: int
{
     return 1; // 推論された型: 1 (int リテラル)
}
```

このエラーが発生します：`LessSpecificReturnType - 関数aの推論された戻り値の型 '1' は、宣言された戻り値の型 'int' よりも具体的です`

エラーを修正するには、docブロックでより具体的な型を指定する必要があります：
```php
/** 
 * @return 1 
 */
function getOne(): int {
     return 1;
}
```

**警告**: より厳密な型を強制することは必ずしも最良の対処法ではなく、予期しない結果を招く可能性があります。`restrictReturnTypes="true"`では、次のコードは無効です：

```php
class StandardCar {
    /**
     * @return list{'motor', 'brakes', 'wheels'}
     */
    public function getSystems(): array {
        return ['motor', 'brakes', 'wheels'];
    }
}

class PremiumCar extends StandardCar {
    /**
     * @return list{'motor', 'brakes', 'wheels', 'rear parking sensor'}
     */
    public function getSystems(): array {
        return ['motor', 'brakes', 'wheels', 'rear parking sensor'];
    }
}
```

``ImplementedReturnTypeMismatch - StandardCar::getSystemsの継承された戻り値の型 'list{'motor', 'brakes', 'wheels'}' は、PremiumCar::getSystemsの実装された戻り値の型 'list{'motor', 'brakes', 'wheels', 'rear parking sensor'}' と異なります`

#### findUnusedBaselineEntry
使用されていないベースラインエントリに対して[UnusedBaselineEntry](issues/UnusedBaselineEntry.md)を発行します。

#### findUnusedIssueHandlerSuppression
問題を抑制するために使用されていない抑制された問題ハンドラに対して[UnusedIssueHandlerSuppression](issues/UnusedIssueHandlerSuppression.md)を発行します。

## プロジェクト設定

#### &lt;projectFiles&gt;
Psalmが検査すべきすべてのディレクトリのリストを含みます。`<ignoreFiles>`ディレクティブを使用して、無視するファイルやフォルダのセットを指定することもできます。デフォルトでは、無視されるファイル/フォルダは存在する必要があります。存在しないかもしれない無視されるファイル/フォルダには`allowMissingFiles`属性を追加できます。

```xml
<projectFiles>
  <directory name="src" />
  <ignoreFiles>
    <directory name="src/Stubs" />
  </ignoreFiles>
  <ignoreFiles allowMissingFiles="true">
    <directory name="path-that-may-not-exist" />
  </ignoreFiles>
</projectFiles>
```

#### &lt;extraFiles&gt;
オプション。`<projectFiles>`と同じ形式です。Psalmが読み込むべきが検査しないディレクトリ。

#### &lt;fileExtensions&gt;
オプション。検索する拡張子のリスト。これを拡張する方法を理解するには、[非PHPファイルのチェック](checking_non_php_files.md)を参照してください。

#### &lt;enableExtensions&gt;
オプション。有効にする拡張機能のリスト。デフォルトでは、composer.jsonで必要とされる拡張機能のみが有効になります。

```xml
<enableExtensions>
  <extension name="decimal"/>
  <extension name="pdo"/>
</enableExtensions>
```

#### &lt;disableExtensions&gt;
オプション。無効にする拡張機能のリスト。デフォルトでは、composer.jsonで必要とされる拡張機能のみが有効になります。

```xml
<disableExtensions>
  <extension name="gmp"/>
</disableExtensions>
```

#### &lt;plugins&gt;
オプション。`<plugin filename="path_to_plugin.php" />`エントリのリスト。詳細は[プラグイン](plugins/using_plugins.md)セクションを参照してください。

#### &lt;issueHandlers&gt;
オプション。Psalmが見つけたすべての問題について苦情を言うことを望まない場合、issueHandlerタグを使用してそれを設定できます。詳細は[コードの問題への対処](dealing_with_code_issues.md)を参照してください。

#### &lt;mockClasses&gt;
オプション。テストでモッククラスを使用していますか？ファイルをチェックする際にPsalmがそれらを無視するようにしたい場合は、`<class name="Your\Namespace\ClassName" />`でクラスの完全修飾パスを含めてください。

#### &lt;universalObjectCrates&gt;
オプション。静的に決定できないプロパティを持つオブジェクトがありますか？Psalmが特定のクラスライクのすべてのプロパティを混合として扱うようにしたい場合は、`<class name="Your\Namespace\ClassName" />`でクラスの完全修飾パスを含めてください。デフォルトでは、`stdClass`と`SimpleXMLElement`が汎用オブジェクトクレートとして設定されています。

#### &lt;stubs&gt;
オプション。コードベースがPsalmにリフレクションを介して見えないクラスや関数を使用している場合（例えば、Psalmを実行しているマシンで利用できない内部パッケージにコードベースが依存している場合）、スタブファイルを使用できます。PhpStorm（人気のあるIDE）などで使用されているスタブは、実装なしでクラスと関数の説明を提供します。

一般的なクラスのスタブのリストは[こちら](https://github.com/JetBrains/phpstorm-stubs)で見つけることができます。

各ファイルを`<file name="path/to/file.php" />`でリストアップしてください。テストするクラスがスタブファイルで定義された親クラスまたはインターフェースを使用している場合、このスタブは`preloadClasses="true"`属性で設定する必要があります。

```xml
<stubs>
  <file name="path/to/file.php" />
  <file name="path/to/abstract-class.php" preloadClasses="true" />
</stubs>
```

#### &lt;ignoreExceptions&gt;
オプション。`checkForThrowsDocblock`または`checkForThrowsInGlobalScope`で報告しない例外のリスト。`class`タグはspecifiedクラスのインスタンスのみをPsalmに無視させ、`classAndDescendants`はサブクラスも無視させます。例外に`onlyGlobalScope="true"`が設定されている場合、その例外に対しては`checkForThrowsInGlobalScope`のみが無視されます。例：

```xml
<ignoreExceptions>
  <class name="fully\qualified\path\Exc" onlyGlobalScope="true" />
  <classAndDescendants name="fully\qualified\path\OtherExc" />
</ignoreExceptions>
```

#### &lt;globals&gt;
オプション。コードベースが`global`キーワードでアクセスするグローバル変数を使用している場合、その型を宣言できます。例：

```xml
<globals>
  <var name="globalVariableName" type="type" />
</globals>
```

一部のフレームワークやライブラリは、例えば`$GLOBALS[DB]->query($query)`を通じて機能を公開しています。
以下の設定は、スーパーグローバル（`$GLOBALS`、`$_GET`など）にカスタム型を宣言します。

```xml
<globals>
  <var name="GLOBALS" type="array{DB: MyVendor\DatabaseConnection, VIEW: MyVendor\TemplateView}" />
  <var name="_GET" type="array{data: array<string, string>}" />
</globals>
```

上の例は、以下のようにグローバル変数を宣言します：
- `$GLOBALS`
    - `DB`の型は`MyVendor\DatabaseConnection`
    - `VIEW`の型は`MyVendor\TemplateView`
- `$_GET`
    - `data`は例えば`["id" => "123", "title" => "Nice"]`のような型

#### &lt;forbiddenFunctions&gt;
オプション。[`ForbiddenCode`](issues/ForbiddenCode.md)問題タイプを発行すべき関数のリストを指定できます。

```xml
<forbiddenFunctions>
  <function name="var_dump" />
  <function name="dd" />
</forbiddenFunctions>
```

## プラグインでのPsalm設定へのアクセス
プラグインは、[シングルトンPsalm\Config](https://github.com/vimeo/psalm/blob/master/src/Psalm/Config.php)を使用してプラグインでグローバル設定にアクセスまたは変更できます。

```php
$config = \Psalm\Config::getInstance();
if (!isset($config->globals['$GLOBALS'])) {
    $config->globals['$GLOBALS'] = 'array{data: array<string, string>}';
}
```


# コンフィギュレーション

PsalmはXMLコンフィグファイル(デフォルトでは`psalm.xml`)を使用します。基本的な例は以下のようになります：

```xml
<?xml version="1.0"?>
<psalm>
    <projectFiles>
        <directory name="src" />
    </projectFiles>
</psalm>
```

設定ファイルは、[XInclude](https://www.w3.org/TR/xinclude/) タグを使用して複数のファイルに分割することができます (前の例を参照)：#### psalm.xml
```xml
<?xml version="1.0"?>
<psalm
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xmlns="https://getpsalm.org/schema/config"
    xsi:schemaLocation="https://getpsalm.org/schema/config vendor/vimeo/psalm/config.xsd"
    xmlns:xi="http://www.w3.org/2001/XInclude"
>
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

#### エラーレベル

```xml
<psalm
  errorLevel="[int]"
/>
```
これはpsalmの[error-detection level](error_levels.md) に対応している。

#### reportMixedIssues

```xml
<psalm
  reportMixedIssues="[bool]"
/>
```
これを`"false"` に設定すると、Psalmの出力で`Mixed` タイプのすべての問題が非表示になります。省略すると、`errorLevel` が 3 以上の場合は`"false"` に、エラー・レベルが 1 または 2 の場合は`"true"` になります。

#### totallyTyped

\(非推奨) この設定は、`errorLevel` が 1 のときに自動的に有効になる`reportMixedIssues` に置き換えられました。

#### resolveFromConfigFile

```xml
<psalm
  resolveFromConfigFile="[bool]"
/>
```
これが有効な場合、設定ファイル内で言及されている相対ディレクトリは、設定 ファイルの場所に対して相対的に解決されます。無効になっているか、指定されていない場合は、Psalm プロセスの作業ディレクトリを基準に解決されます。

Psalm の新しいバージョンでは、設定ファイルを生成するときにこのオプションが有効になります。古いバージョンには含まれていません。

#### useDocblockTypes

```xml
<psalm
  useDocblockTypes="[bool]"
>
```
docblockで定義されている型を使用するかどうか。デフォルトは`true` 。

#### useDocblockPropertyTypes

```xml
<psalm
  useDocblockPropertyTypes="[bool]"
>
```
すべてのdocblock型を使用しない場合でも、docblockプロパティ型を使用することができます。デフォルトは`false` です(ただし、`useDocblockTypes` が`false` の場合のみ関係します)。

#### docblockPropertyTypesSealProperties

```xml
<psalm
  docblockPropertyTypesSealProperties="[bool]"
>
```
docblockクラスで@propertyを使用する際に、@psalm-seal-propertiesを指定するかどうか。デフォルトは`true` です。

#### usePhpDocMethodsWithoutMagicCall

```xml
<psalm
  usePhpDocMethodsWithoutMagicCall="[bool]"
>
```
PHPDoc`@method` アノテーションは、通常は`__call` メソッドを持つクラスにのみ適用されます。これを`true` に設定すると、`@method` アノテーションを使用して継承したメソッドの返り値の型を上書きできるようになります。デフォルトは`false` です。

#### usePhpDocPropertiesWithoutMagicCall

```xml
<psalm
  usePhpDocPropertiesWithoutMagicCall="[bool]"
>
```
PHPDoc`@property`,`@property-read` および`@property-write` のアノテーションは、 通常は`__get`/`__set` のメソッドを持つクラスにのみ適用されます。これを`true` に設定すると、`@property` 、`@property-read` および`@property-write` アノテーションを使用してプロパティの存在チェックや結果のプロパティ型を上書きすることができます。デフォルトは`false` です。

#### disableVarParsing

```xml
<psalm
  disableVarParsing="[bool]"
/>
```

`@var` PHPDocs のプロパティを除くすべてのパースを無効にします。これを`true` に設定すると、Psalm ジェネリックスの統合や適切な型付けの前に使用された古い`@var` アノテーションによる誤検出を取り除くことができます。デフォルトは`false` です。

#### strictBinaryOperands

```xml
<psalm
  strictBinaryOperands="[bool]"
>
```
true の場合、数値演算と文字列演算に厳格な型付けを強制します (https://github.com/vimeo/psalm/issues/24 を参照)。デフォルトは`false` です。

#### rememberPropertyAssignmentsAfterCall

```xml
<psalm
  rememberPropertyAssignmentsAfterCall="[bool]"
>
```
これを`false` に設定すると、関数を呼び出すと、Psalmは現在解析している関数のスコープ内のオブジェクト・プロパティについて知っていたことをすべて忘れてしまいます。これはHackが持っている機能と重複します。デフォルトは`true` です。

#### 許容StringToStandInForClass

```xml
<psalm
  allowStringToStandInForClass="[bool]"
>
```
`true` `$some_string::someMethod()` の場合、文字列をクラスとして使用することができます。`false` `InvalidStringClass` の場合、クラス定数文字列 (`Foo\Bar::class` の形式) だけがクラスとして使用できます。デフォルトは`false` です。

#### disableSuppressAll

```xml
<psalm
  disableSuppressAll="[bool]"
>
```
`true` の場合、すべてのissueのワイルドカード抑制を`@psalm-suppress all` で無効にします。デフォルトは`false` です。

#### memoizeMethodCallResults

```xml
<psalm
  memoizeMethodCallResults="[bool]"
>
```
`true` の場合、引数が渡されないメソッド呼び出しの結果は、指定されたオブジェクトでそのメソッドが繰り返し呼び出される間に記憶されます。デフォルトは`false` です。

#### hoistConstants

```xml
<psalm
  hoistConstants="[bool]"
>
```
`true` の場合、ファイル内の関数で定義された定数は、その関数を呼び出すときだけでなく、 そのファイルを必要とするときにも使用可能であるとみなされます。デフォルトは`false` です（つまり、関数内で定義された定数は、その関数が呼び出されたときに *のみ* 使用可能になります）。

#### addParamDefaultToDocblockType

```xml
<psalm
  addParamDefaultToDocblockType="[bool]"
>
```
時々、paramのデフォルトがdocblockタイプと一致しないことがあります。デフォルトでは、Psalmは問題を出します。このフラグを`true` に設定すると、paramのデフォルトを含むようにparamの型を展開します。デフォルトは`false` です。

#### checkForThrowsDocblock
```xml
<psalm
  checkForThrowsDocblock="[bool]"
>
```
`true` の場合、Psalm は、指定された関数やメソッドでスローされたすべての例外について、開発者が`@throws` docblock を提供しているかどうかをチェックします。デフォルトは`false` です。

#### checkForThrowsInGlobalScope
```xml
<psalm
  checkForThrowsInGlobalScope="[bool]"
>
```
`true` の場合、Psalm は開発者がグローバルスコープですべての例外を捕捉したかどうかをチェックします。デフォルトは`false` です。

#### ignoreInternalFunctionFalseReturn

```xml
<psalm
  ignoreInternalFunctionFalseReturn="[bool]"
>
```
`true` の場合、Psalm は（`preg_split` のような）内部関数の戻り値に起因する、false を返す可能性のある問題を無視します。デフォルトは`false` です。

#### ignoreInternalFunctionNullReturn

```xml
<psalm
  ignoreInternalFunctionNullReturn="[bool]"
>
```
`true` の場合、Psalm は内部配列関数 (`current` のようなもの) の戻り値に起因する NULL の問題を無視します。デフォルトは`false` です。

#### inferPropertyTypesFromConstructor

```xml
<psalm
  inferPropertyTypesFromConstructor="[bool]"
>
```
`true` の場合、Psalm はストレート・コンストラクタに見られる代入からプロパティ・タイプを推論します。デフォルトは`true` です。

#### findUnusedVariablesAndParams
```xml
<psalm
  findUnusedVariablesAndParams="[bool]"
>
```
`true` の場合、Psalm はすべての未使用変数を見つけようとします。`--find-unused-variables` で実行するのと同等です。デフォルトは`false` です。

#### findUnusedCode
```xml
<psalm
  findUnusedCode="[bool]"
>
```
`true` `--find-unused-code` の場合、Psalm はすべての未使用コード (未使用変数を含む) を見つけようとします。デフォルトは`false` です。

#### findUnusedPsalmSuppress
```xml
<psalm
  findUnusedPsalmSuppress="[bool]"
>
```
`true` `--find-unused-psalm-suppress` の場合、Psalm は`@psalm-suppress` のアノテーションのうち、使用されていないものをすべて報告します。デフォルトは`false` です。

#### ensureArrayStringOffsetsExist
```xml
<psalm
  ensureArrayStringOffsetsExist="[bool]"
>
```
`true` の場合、`$arr['foo']` のような配列上の明示的な文字列オフセットが存在することをユーザが最初に (`isset` で確認するか、オブジェクトライクな配列で確認することなく) 参照すると、Psalm は文句を言います。デフォルトは`false` です。

#### ensureArrayIntOffsetsExist
```xml
<psalm
  ensureArrayIntOffsetsExist="[bool]"
>
```
`true` の場合、`$arr[7]` のような配列の明示的な整数オフセットを参照する際に、ユーザがその存在を (`isset` のチェックまたはオブジェクトのような配列を介して) 最初に表明しない場合、Psalm は文句を言います。デフォルトは`false` です。

#### ensureOverrideAttribute
```xml
<psalm
  ensureOverrideAttribute="[bool]"
>
```
`true` の場合、Psalm は、親のメソッドをオーバーライドするが、`Override` 属性を持たないクラスとインターフェースのメソッドを報告します。デフォルトは`false` です。

#### phpVersion
```xml
<psalm
  phpVersion="[string]"
>
```
プロジェクトをチェックしたり修正したりするときに Psalm が想定する php のバージョンを設定します。この属性が設定されていない場合、Psalm は`composer.json` の宣言を使用します。宣言された`php` の依存関係を満たす PHP の最も古いバージョンと照合します。

これは、コマンドラインで`--php-version=` フラグを指定することで上書きすることができます。 このフラグは、`phpVersion` の設定や、`composer.json` のバージョンよりも優先されます。

#### skipChecksOnUnresolvableIncludes
```xml
<psalm
  skipChecksOnUnresolvableIncludes="[bool]"
>
```

`true` の場合、Psalm は解決できない`include` または`require` に出くわした後、クラス、変数、関数のチェックをスキップします。これにより、Psalm が知らない関数やクラスを参照できるようになります。

デフォルトは`false` です。

#### sealAllMethods

```xml
<psalm
  sealAllMethods="[bool]"
>
```

`true` を指定すると、Psalm はすべてのクラスを sealed メソッドを持っているものとして扱います。つまり、マジックメソッド`__call` を実装する場合は、マジックメソッドごとに`@method` も追加する必要があります。デフォルトはfalseです。

#### sealAllProperties

```xml
<psalm
  sealAllProperties="[bool]"
>
```

`true` の場合、Psalm はすべてのクラスを、あたかもシーリングされたプロパティを持つかのように扱います。つまり、`@property` （または`@property-read`/`@property-write` ）アノテーションのリストに含まれておらず、`property` として明示的に定義されていないプロパティは、取得も設定も許可されません。 デフォルトは false です。

#### runTaintAnalysis

```xml
<psalm
  runTaintAnalysis="[bool]"
>
```

`true` の場合、Psalm はあなたのコードベース上で[Taint Analysis](../security_analysis/index.md) を実行します。この設定は、`--taint-analysis` で Psalm を実行する場合と同じです。

#### reportInfo

```xml
<psalm
  reportInfo="[bool]"
>
```

`false` の場合、Psalmは`errorLevel` よりも低いレベルの問題を`info` と見なしません(代わりに抑制されます)。これは大規模なプロジェクトでは分析時間の大きな改善になります。しかし、この設定はPsalmが抑制された問題をカウントしたり、修正を提案したりすることを防ぎます。

#### allowNamedArgumentCalls

```xml
<psalm
  allowNamedArgumentCalls="[bool]"
>
```

`false` の場合、Psalmはあなたのコードで`ParamNameMismatch` の問題を報告しなくなります。これは、ライブラリのメソッドへの外部からのアクセスを防ぐための個々の`@no-named-arguments` の使用や、variadics を使用する際に型を`list` に減らすことに取って代わるものではありません。

#### triggerErrorExits

```xml
<psalm
   triggerErrorExits="[string]"
>
```

trigger_errorの動作を記述する。`always` は常に終了する、`never` は終了しない、`default` は`E_USER_ERROR` の場合のみ終了する。デフォルトは`default`

### 実行中のPsalm

#### オートローダー
```xml
<psalm
  autoloader="[string]"
>
```
アプリケーションが1つ以上のカスタムオートローダーを登録したり、ユニバーサル定数/関数を宣言したりした場合、このオートローダースクリプトはスキャン開始前にPsalmによって実行されます。Psalmはデフォルトで常にcomposerのオートローダーを登録します。

#### throwExceptionOnError
```xml
<psalm
  throwExceptionOnError="[bool]"
>
```
Psalm がエラーに遭遇したときに、通常の古い例外を投げるようにします。デフォルトは`false` 。

#### hideExternalErrors
```xml
<psalm
  hideExternalErrors="[bool]"
>
```
プロジェクト・ファイルで使用されているが `<projectFiles>`.デフォルトは`false` です。

#### 非表示にするかどうかを設定します。
```xml
<psalm
  hideAllErrorsExceptPassedFiles="[bool]"
>
```
CLIで明示的に引数として渡されたファイルに対してのみ問題を報告するかどうか。これは、CLI で設定されていない場合、require/include で読み込まれたファイルは報告されないことを意味します。単一または選択したファイルのエラーだけをチェックしたい場合に便利です。デフォルトは`false` 。

#### cacheDirectory
```xml
<psalm
  cacheDirectory="[string]"
>
```
Psalm のキャッシュデータを格納するディレクトリ。指定する場合 (そしてそれがまだ存在しない場合)、その親ディレクトリが既に存在しなければならない。

デフォルトは`$XDG_CACHE_HOME/psalm` です。`$XDG_CACHE_HOME` が設定されていないか空の場合、`$HOME/.cache/psalm` と同じデフォルトが使用され、定義されていない場合は`sys_get_temp_dir() . '/psalm'` が使用されます。

#### allowFileIncludes
```xml
<psalm
  allowFileIncludes="[bool]"
>
```
PHP 内で`require`/`include` の呼び出しを許可するかどうか。デフォルトは`true` です。

#### シリアライザ
```xml
<psalm
  serializer="['igbinary'|'default']"
>
```
Psalm がデータをキャッシュする際に使用するシリアライザをハードコードします。デフォルトでは、バージョンが 2.0.5 以上の場合は`ext-igbinary` を使用し、それ以外の場合は PHP 組み込みのシリアライザを使用します。

#### コンプレッサー
```xml
<psalm
  compressor="['lz4'|'deflate'|'off']"
>
```
Psalm のキャッシュの圧縮方式をハードコードできるようにします。デフォルトでは、Psalm は`ext-zlib` deflate を使用します。

#### スレッド
```xml
<psalm
        threads="[int]"
>
```
Psalm が使用するスレッド数をハードコードできるようにします (コマンドラインの`--threads` に似ています)。この値は、ホストマシンからスレッドを検出する代わりに使用されますが、コマンドラインで`--threads` または`--debug` (スレッドを 1 に設定) を使用すると上書きされます。

#### maxStringLength
```xml
<psalm
  maxStringLength="1000"
>
```
この設定は、Psalm解析中にリテラル文字列型に変換されるリテラル文字列の最大長を制御します。   この値 (既定では 1000 バイト) より長い文字列は、一般的な`non-empty-string` 型に変換されます。  

この設定を変更すると、望ましくない副作用が発生する可能性があり、その副作用はバグとはみなされないことに注意してください。  

#### maxShapedArraySize
```xml
<psalm
  maxShapedArraySize="100"
>
```
この設定は、Psalm 分析中に定形`array{key1: "value", key2: T}` 型に変換される定形配列の最大サイズを制御する。   この値（デフォルトでは 100）より大きい配列は、代わりに一般的な`non-empty-array` 型に変換されます。  

この設定を変更すると、望ましくない副作用が発生する可能性があり、その副作用はバグとはみなされないことに注意してください。  

#### restrictReturnTypes

```xml
<psalm
  restrictReturnTypes="true"
>
```

宣言された戻り値の型が推論された戻り値の型ほど厳密でない場合に`LessSpecificReturnType` を発行します。

このコードは
```php
function getOne(): int // declared type: int
{ 
    return 1; // inferred type: 1 (int literal)
}
```
このエラーを出す：`LessSpecificReturnType - The inferred return type '1' for a is more specific than the declared return type 'int'`

エラーを修正するには、doc-blockでより具体的な型を指定する必要があります：
```php
/**
 * @return 1
 */
function getOne(): int 
{ 
    return 1;
}
```

**警告**：警告**: 強制的な型指定は必ずしも最善の方法とは限らず、予期せぬ結果を引き起こす可能性があります。次のコードは
`restrictReturnTypes="true"`:
```php
class StandardCar {     /**      * @return list{'motor', 'brakes', 'wheels'}      */     public function getSystems(): array {         return ['motor', 'brakes', 'wheels'];     } }

class PremiumCar extends StandardCar {     /**      * @return list{'motor', 'brakes', 'wheels', 'rear parking sensor'}      */     public function getSystems(): array {         return ['motor', 'brakes', 'wheels', 'rear parking sensor'];     } }
```
`ImplementedReturnTypeMismatch - The inherited return type 'list{'motor', 'brakes', 'wheels'}' for StandardCar::getSystems is different to the implemented return type for PremiumCar::getsystems 'list{'motor', 'brakes', 'wheels', 'rear parking sensor'}'`

#### で無効です。

ベースライン・エントリがissueの抑制に使用されていない場合に[UnusedBaselineEntry](issues/UnusedBaselineEntry.md) 。

を返します #### findUnusedIssueHandlerSuppression

抑制されたissueハンドラがissueの抑制に使用されていない場合に[UnusedIssueHandlerSuppression](issues/UnusedIssueHandlerSuppression.md) 。

## プロジェクトの設定

#### &lt;projectFiles&gt; Psalmが検査すべきすべてのディレクトリのリストを含みます。ディレクティブで無視するファイルやフォルダを指定することもできます。 `<ignoreFiles>`ディレクティブで指定することもできます。  デフォルトでは、無視するファイルやフォルダは存在する必要があります。  無視されるファイルやフォルダーは、存在してもしなくても構いませんが、`allowMissingFiles` 属性を追加することができます。
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

#### &lt;extraFiles&gt; オプション。以下と同じ。 `<projectFiles>`.Psalmが読み込むが検査しないディレクトリ。

#### &lt;fileExtensions&gt; オプション。検索する拡張子のリスト。これを拡張する方法については[Checking non-PHP files](checking_non_php_files.md) を参照のこと。

#### &lt;enableExtensions&gt; オプション。有効にする拡張子のリスト。デフォルトでは、composer.jsonで必要な拡張機能のみが有効になります。
```xml
<enableExtensions>
  <extension name="decimal"/>
  <extension name="pdo"/>
</enableExtensions>
```

#### &lt;disableExtensions&gt; オプション。無効にする拡張機能のリスト。デフォルトでは、composer.jsonで必要な拡張機能のみが有効になります。
```xml
<disableExtensions>
  <extension name="gmp"/>
</disableExtensions>
```

#### &lt;plugins&gt; オプション。プラグインの `<plugin filename="path_to_plugin.php" />`エントリーのリスト。詳しくは[Plugins](plugins/using_plugins.md) セクションを参照のこと。

#### &lt;issueHandlers&gt; オプション。Psalmが見つけたissueにいちいち文句をつけたくない場合は、issueHandlerタグで設定できます。[Dealing with code issues](dealing_with_code_issues.md) 。

#### &lt;mockClasses&gt; オプション。テストでモッククラスを使いますか？もし Psalm がファイルをチェックするときにモッククラスを無視したい場合は、モッククラスへの完全修飾パスを `<class name="Your\Namespace\ClassName" />`

#### &lt;universalObjectCrates&gt; オプション。静的に決定できないプロパティを持つオブジェクトがありますか？Psalmに、指定されたクラスライクなオブジェクトのすべてのプロパティを混合プロパティとして扱わせたい場合は、クラスへの完全修飾パスを `<class name="Your\Namespace\ClassName" />`.デフォルトでは、`stdClass` と`SimpleXMLElement` はユニバーサル・オブジェクト・クレートに設定されています。

#### &lt;stubs&gt; オプション。コードベースがリフレクションによって Psalm から見えないクラスや関数を使用している場合 (コードベースが依存している内部パッケージが Psalm を実行しているマシンでは利用できない場合など)、スタブファイルを使用することができます。PhpStorm（一般的なIDE）などで使用されているスタブは、実装を含まないクラスや関数の説明を提供します。

一般的なクラスのスタブのリストは[here](https://github.com/JetBrains/phpstorm-stubs) にあります。各ファイルを `<file name="path/to/file.php" />`.テスト対象のクラスがスタブファイルで定義された親クラスやインターフェイスを使用している場合、このスタブは属性`preloadClasses="true"` で設定する必要があります。

```xml
<stubs>
  <file name="path/to/file.php" />
  <file name="path/to/abstract-class.php" preloadClasses="true" />
</stubs>
```

&lt;ignoreExceptions&gt; オプション。`checkForThrowsDocblock` または`checkForThrowsInGlobalScope` に対して報告しない例外のリスト。`class` タグは Psalm に指定されたクラスのインスタンスだけを無視させ、`classAndDescendants` は Psalm にサブクラスも無視させます。ある例外で`onlyGlobalScope` が`true` に設定されている場合、その例外では`checkForThrowsInGlobalScope` だけが無視されます。
```xml
<ignoreExceptions>
  <class name="fully\qualified\path\Exc" onlyGlobalScope="true" />
  <classAndDescendants name="fully\qualified\path\OtherExc" />
</ignoreExceptions>
```

#### &lt;globals&gt; オプション。あなたのコードベースが`global` キーワードでアクセスされるグローバル変数を使用している場合、その型を宣言することができます。
```xml
<globals>
  <var name="globalVariableName" type="type" />
</globals>
```

フレームワークやライブラリの中には、`$GLOBALS[DB]->query($query)` などを通して機能を公開しているものがあります。以下のコンフィギュレーションでは、スーパーグローバル（`$GLOBALS`,`$_GET`, ...）のカスタム・タイプを宣言します。

```xml
<globals>
  <var name="GLOBALS" type="array{DB: MyVendor\DatabaseConnection, VIEW: MyVendor\TemplateView}" />
  <var name="_GET" type="array{data: array<string, string>}" />
</globals>
```

上記の例では、グローバル変数を以下のように宣言しています。

-`$GLOBALS` -`DB` 型の`MyVendor\DatabaseConnection` -`VIEW` 型の`MyVendor\TemplateView` -`$_GET` -`data` 例えば次のように。`["id" => "123", "title" => "Nice"]`

#### &lt;forbiddenFunctions&gt; オプション。[`ForbiddenCode`](issues/ForbiddenCode.md) issueタイプを出力する関数のリストを指定します。

```xml
<forbiddenFunctions>
  <function name="var_dump" />
  <function name="dd" />
</forbiddenFunctions>
```

## プラグインでPsalm設定にアクセスする

プラグインは、[singleton Psalm\Config](https://github.com/vimeo/psalm/blob/master/src/Psalm/Config.php) を使って、プラグインのグローバル設定にアクセスしたり、変更したりすることができます。

```php
$config = \Psalm\Config::getInstance();
if (!isset($config->globals['$GLOBALS'])) {
    $config->globals['$GLOBALS'] = 'array{data: array<string, string>}';
}
```

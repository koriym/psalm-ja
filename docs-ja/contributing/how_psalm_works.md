# psalmの働き

すべての分析の入り口は[`ProjectAnalyzer`](https://github.com/vimeo/psalm/blob/master/src/Psalm/Internal/Analyzer/ProjectAnalyzer.php)

`ProjectAnalyzer` は2つのことを担当する：スキャンと分析

## スキャン

解析フェーズをマルチスレッドで実行できるようにするため、Psalmはファイルやファイル群に対して、可能性のあるすべての依存関係を決定し、その関数のシグネチャと定数を取得する必要がある。

スキャンは`Psalm\Internal\Codebase\Scanner` で行われます。

最初のタスクは、ファイルを[PHP Parser](https://github.com/nikic/PHP-Parser) の文の集合に変換することです。PHP Parserは、PHPコードを抽象構文木に変換し、Psalmがすべての解析に使用します。

### ディープスキャンとシャロースキャンの比較

`NodeVisitor` [`ReflectorVisitor`](https://github.com/vimeo/psalm/blob/master/src/Psalm/Internal/PhpVisitor/ReflectorVisitor.php) これは、関数のシグネチャ、戻り値型、定数、継承を取得するだけの浅いスキャンと、関数のすべての文に潜り込んで依存関係(関数がインスタンス化するクラス名など)を取得する深いスキャンの2つのモードがあります。ディープスキャンは、後で分析されることがわかっているファイルに対してのみ行われます（したがって、例えばベンダーディレクトリの大部分は、シャロースキャンを受けるだけです）。

そのため、`src` ディレクトリを分析する場合、Psalm は以下のファイルをディープスキャンします：

src/A.php
```php
<?php use Vendor\VendorClass; use Vendor\OtherVendorClass;

class A extends VendorClass {     public function foo(OtherVendorClass $c): void {} }
```

また、`Vendor\VendorClass` に属するファイルもディープスキャンします。これは、ある時点でプロパティのインスタンスをチェックする必要があるかもしれないからです。

変数`$c` のメソッドシグネチャと戻り値の型にしか関心がないため、`Vendor\OtherVendorClass` (および依存するすべてのファイル) を浅くスキャンします。

### クラス名からファイルを見つける

`ClassName` =&gt;`src/FileName.php` のマッピングを把握するために、プロジェクト・ファイルにはリフレクションを使い、ベンダー・ファイルにはComposerクラスマップを使う。

### スキャンからデータを保存する

`ReflectorVisitor` がアクセスする各ファイルに対して、Psalm は[`FileStorage`](https://github.com/vimeo/psalm/blob/master/src/Psalm/Storage/FileStorage.php) インスタンスを作成し、ファイルの内容に応じて[`ClassLikeStorage`](https://github.com/vimeo/psalm/blob/master/src/Psalm/Storage/ClassLikeStorage.php) と[`FunctionLikeStorage`](https://github.com/vimeo/psalm/blob/master/src/Psalm/Storage/FunctionLikeStorage.php) インスタンスも作成する。

すべてのファイルとそのクラスと関数シグネチャのセットができたら、[`Populator`](https://github.com/vimeo/psalm/blob/master/src/Psalm/Internal/Codebase/Populator.php) クラスのすべての継承を計算し、分析に移る。

スキャン・ステップの最後には、[`ClassLikes`](https://github.com/vimeo/psalm/blob/master/src/Psalm/Internal/Codebase/ClassLikes.php) 、[`Functions`](https://github.com/vimeo/psalm/blob/master/src/Psalm/Internal/Codebase/Functions.php) 、[`Methods`](https://github.com/vimeo/psalm/blob/master/src/Psalm/Internal/Codebase/Methods.php) クラスに必要な情報をすべて入力し、プロジェクトで使用するすべてのクラスとファイルについて、`FileStorage` と`ClassLikeStorage` オブジェクトの完全なリスト（それぞれ[`FileStorageProvider`](https://github.com/vimeo/psalm/blob/master/src/Psalm/Internal/Provider/FileStorageProvider.php) と[`ClassLikeStorageProvider`](https://github.com/vimeo/psalm/blob/master/src/Psalm/Internal/Provider/ClassLikeStorageProvider.php) 内）を作成しました。

## 分析

にあるファイルを分析する。[`FileAnalyzer`](https://github.com/vimeo/psalm/blob/master/src/Psalm/Internal/Analyzer/FileAnalyzer.php)

`FileAnalyzer` 、与えられたファイルを受け取り、トップレベルのコンポーネント（クラス、特性、インターフェイス、関数）を探します。名前空間の内部を調べ、その中のクラス、インターフェイス、特性、関数を抽出することもできます。

これらのコンポーネントの解析は、[`ClassAnalyzer`](https://github.com/vimeo/psalm/blob/master/src/Psalm/Internal/Analyzer/ClassAnalyzer.php) 、[`InterfaceAnalyzer`](https://github.com/vimeo/psalm/blob/master/src/Psalm/Internal/Analyzer/InterfaceAnalyzer.php) 、[`FunctionAnalyzer`](https://github.com/vimeo/psalm/blob/master/src/Psalm/Internal/Analyzer/FunctionAnalyzer.php) に委譲されます。

行ごとの解析は最も基本的なユースケースなので（クラス継承の心配はない）、`FunctionAnalyzer` 。

### 関数解析

`FunctionAnalyzer::analyze` は[`FunctionLikeAnalyzer`](https://github.com/vimeo/psalm/blob/master/src/Psalm/Internal/Analyzer/FunctionLikeAnalyzer.php) で定義されています。このメソッドはまず、スキャン・ステップで作成した、指定された関数の`FunctionLikeStorage` オブジェクトを取得します。この`FunctionLikeStorage` オブジェクトには、関数のパラメータに関する情報があり、それを[`Context`](https://github.com/vimeo/psalm/blob/master/src/Psalm/Context.php) オブジェクトに渡します。`Context` には、変数とプロパティに関するすべての型情報 (`Context::$vars_in_scope` に格納) が含まれています。

`FunctionLikeAnalyzer::analyze` のどこかで新しい[`StatementsAnalyzer`](https://github.com/vimeo/psalm/blob/master/src/Psalm/Internal/Analyzer/StatementsAnalyzer.php) を作成し、その`analyze()` メソッドを呼び出して、PhpParser ノードのセットを渡します。`StatementAnalyzer::analyze` は、より詳細な分析を行うために、さまざまなチェッカー (`IfAnalyzer`,`ForeachAnalyzer`,`ExpressionAnalyzer` など) に渡します。

各行では、`Context` オブジェクトが操作されたりされなかったりします。分岐点（if文、ループ、3項など）では、`Context` オブジェクトがクローンされ、分岐の最後で、Psalmが変更を解決する方法を見つけ出し、クローンされていない`Context` オブジェクトを更新します。

`NodeDataProvider` は各 PhpParser ノードの型を保存します。

すべてのステートメントが分析された後、すべての戻り値の型を集め、与えられた戻り値の型と比較します。

### 型の照合

`Context` オブジェクトの更新は簡単なものもありますが、そうでないものもあります。新しい型情報に照らして`Context` オブジェクトを更新することは、[`Reconciler`](https://github.com/vimeo/psalm/blob/master/src/Psalm/Type/Reconciler.php) で行われる。 は、`[“$a” => “!null”]` のような配列アサーションと、`$a => string|null` のような既存の型情報のリストを受け取り、 のような更新された情報のセットを返す。`$a => string`

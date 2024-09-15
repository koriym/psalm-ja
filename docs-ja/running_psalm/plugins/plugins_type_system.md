# プラグイン: 型システムの内部構造

Psalmの型システムは、プログラム内の変数の型を異なるクラスを使用して表現します。プラグインはこの型情報を受け取り、更新することができます。

## 共用体型（Union types）

使用する可能性が高い全ての型情報は、[共用体型](../../annotating_code/type_syntax/union_types.md)でラップされています。

`Union`クラスのコンストラクタは`Atomic`型の配列を受け取り、これらの型の1つ以上を同時に表現できます。これらはdocコメントの縦棒に対応します。

```php
new Union([new TNamedObject('Foo\\Bar\\SomeClass')]); // docblockのFoo\Bar\SomeClassに相当
new Union([new TString(), new TInt()]); // docblockのstring|intに相当
```

## 原子型（Atomic types）

浮動小数点数、整数、文字列などのプリミティブ型に加えて、配列やクラスがあります。これらは全て[`src/Psalm/Types/Atomic`](https://github.com/vimeo/psalm/tree/master/src/Psalm/Type/Atomic)にあります。

このフォルダ内の全ての非抽象クラスが有効な型であることに注意してください。これらは全て'T'で始まります。

クラスは以下の通りです：

### その他

`TVoid` - `void`型を表し、通常は何も返さない関数/メソッドを注釈するために使用されます。

`TNull` - `null`型を表します。

`TNever` - 例外をスローするか終了する（ビルトインの`exit()`のような）、決して戻らない関数の`no-return`/`never-return`型を表します。また、可能な型がない共用体型（例えば不可能な交差型）にも使用されます。空の配列`[]`は`array<never, never>`型を持ちます。

`TMixed` - `mixed`型を表し、式の型が不明な場合に使用されます。

`TNonEmptyMixed` - 上記と同様ですが、空ではありません。外部で`$x`が`mixed`の場合、`if ($x) {...}`文の中の`$x`に対して生成されます。

`TEmptyMixed` - 上記と同様ですが、空です。外部で`$x`が`mixed`の場合、`if (!$x) {...}`文の中の`$x`に対して生成されます。

`TIterable` - [`iterable`型](https://www.php.net/manual/en/language.types.iterable.php)を表します（`is_iterable`チェックの結果にもなり得ます）。

`TResource` - `resource`型を表します（例：ファイルハンドル）。

`TClosedResource` - クローズされた`resource`型を表します（例：`fclose()`を通じたファイルハンドル）。

`TAssertionFalsy` - ブールコンテキストで計算されたときにfalseに還元される任意の値を表します。これはアサーションに使用されます。

`TConditional` - phpdocの条件付き戻り値型の内部表現です。例えば ($param1 is int ? int : string)

`TIntMask` - そのパラメータのビットマスク組み合わせの結果である型を表します。`int-mask<1, 2, 4>`は`1|2|3|4|5|6|7`に対応します。

`TIntMaskOf` - 上記と同様ですが、コード内の定数への参照と共に使用されます。`int-mask-of<MyClass::CLASS_CONSTANT_*>`は、1、2、4の3つの定数がある場合、`1|2|3|4|5|6|7`に対応します。

`TKeyOf` - 配列のオフセットを表します（例：`key-of<MyClass::CLASS_CONSTANT>`）。

`TValueOf` - 配列または列挙型の値を表します（例：`value-of<MyClass::CLASS_CONSTANT>`）。

`TTemplateIndexedAccess` - ドキュメント化予定

`TTemplateKeyOf` - 配列の型がテンプレートの場合にTKeyOfを使用するときに使用される型を表します。

`TTemplateValueOf` - 配列または列挙型の型がテンプレートの場合にTValueOfを使用するときに使用される型を表します。

`TPropertiesOf` - クラスのプロパティとその型をキー付き配列として表します（例：`properties-of<MyClass>`）

`TTemplatePropertiesOf` - クラスの型がテンプレートの場合にTPropertiesOfを使用するときに使用される型を表します。

`TTypeAlias` - ドキュメント化予定

### スカラースーパータイプ

`TScalar` - `scalar`スーパータイプを表します（`is_scalar`チェックの結果にもなり得ます）。この型は`float`、`int`、`bool`、`string`を包含します。

`TEmptyScalar` - 空でもある`scalar`型を表します。

`TNonEmptyScalar` - 空でない`scalar`型を表します。

### 数値スーパータイプ

`TNumeric` - `numeric`型を表します（`is_numeric`チェックの結果にもなり得ます）。

`TEmptyNumeric` - 空でもある`numeric`型を表します（`is_numeric`と`empty`チェックの結果にもなり得ます）。

### スカラー型

全てのスカラー型にはリテラルバージョンがあります。例：`int`対`int(5)`。

#### 整数

`TInt` - 正確な値が不明な`int`型を表します。

`TLiteralInt` - 正確な数値が既知の整数値を表すために使用されます。

`TIntRange` - 境界のある値を持つintを記述できます（例：`int<1, 5>`）。

#### 浮動小数点数

`TFloat` - 正確な値が不明な`float`型を表します。

`TLiteralFloat` - 正確な数値が既知の浮動小数点値を表すために使用されます。

#### ブール値

`TBool`、`TFalse`、`TTrue`

`TBool` - 正確な値が不明な`bool`型を表します。

`TFalse` - `false`値型を表します。

`TTrue` - `true`値型を表します。

```php
/** @return string|false    文字列が空の場合はfalse、それ以外はパラメータの最初の文字 */
function firstChar(string $s) { return empty($s) ? false : $s[0]; }
```

ここで、関数はtrueを返すことはありませんが、falseをboolに置き換えなければならない場合、Psalmはtrueを可能な戻り値として考慮しなければなりません。より狭い型を使用することで、次のような無意味なコードを報告できます（https://psalm.dev/r/037291351d）：

```php
$first = firstChar("sdf");
if (true === $first) {
  echo "これは実際にはデッドコードです";
}
```

#### 文字列

`TString` - 正確な値が不明な`string`型を表します。

`TNonEmptyString` - 空でない文字列を表します。

`TNumericString` - 数値でもある文字列を表します（例：`"5"`）。`is_string($s) && is_numeric($s)`の結果になり得ます。

`TLiteralString` - 値が既知の文字列を表すために使用されます。

`TClassString` - `class-string`型を表し、有効なPHPクラスを表す文字列を記述するために使用されます。クラスが派生する親タイプは、コンストラクタで指定される場合とされない場合があります。

`TLiteralClassString` - 特定のクラス文字列を表し、`A::class`のような式によって生成されます。

`TTraitString` - `trait-string`型を表し、有効なPHPトレイトを表す文字列を記述するために使用されます。

`TDependentGetClass` - get_class($var)によって見つかる完全修飾クラスである文字列の値を表します。

`TDependentGetDebugType` - get_debug_type($var)によって見つかる型の値である文字列を表します。

`TDependentGetType` - gettype($var)によって見つかる型の値である文字列を表します。

`TCallableString` - `callable-string`型を表し、`callable`でもある未知の文字列を表すために使用されます。

`TSqlSelectString` - これは特殊な型で、特にプラグインによる消費のためのものです。

`TLowercaseString` - 全ての文字が小文字である文字列を表します（`strtolower`呼び出しの結果にもなり得ます）。

`TNonEmptyLowercaseString` - 全ての文字が小文字である空でない文字列を表します（`strtolower`呼び出しの結果にもなり得ます）。

`TSingleLetter` - 長さが1の文字列を表します。

#### スカラークラス定数

`TScalarClassConstant` - 値がまだ知られていない可能性のあるクラス定数を表します。

#### 配列キースーパータイプ

`TArrayKey` - `array-key`型を表し、`array`のオフセットになり得るものに使用されます。

### 配列

`TArray` - `array<TKey, TValue>`形式の単純な配列を表します。両方が共用体型である2つの要素を持つ配列を期待します。

`TNonEmptyArray` - 上記と同様ですが、空でないことが分かっている配列を表します。

`TKeyedArray` - 'オブジェクトライク配列'を表します - 既知のキーを持つ配列です。

```php
$x = ["a" => 1, "b" => 2]; // TKeyedArrayです、array{a: int, b: int}
$y = rand(0, 1) ? ["a" => null] : ["a" => 1, "b" => "b"]; // オプションのキー/値を持つTKeyedArrayです、array{a: ?int, b?: string}
```

この型は、リストを表すためにも使用されます（現在非推奨の`TList`型の代わりに）。

全ての連想配列がオブジェクトライクとみなされるわけではないことに注意してください。キーが不明な場合、配列は2つの型間のマッピングとして扱われます。

```php
$a = [];
foreach (range(1,1) as $_) $a[(string)rand(0,1)] = rand(0,1); // array<string,int>
```

`TCallableKeyedArray` - `callable`でもあるオブジェクトライク配列を表します。

`TClassStringMap` - 各値の型がその文字列キー値の関数である配列を表します。

### 呼び出し可能およびクロージャ

`TCallable` - `callable`型を表します。`is_callable`チェックの結果になり得ます。
`TClosure` - `Closure`型を表します。

`TCallable`と`TClosure`はオプションでパラメータと戻り値の型も定義できます。

### オブジェクトスーパータイプ

`TObject` - `object`型を表します。

`TObjectWithProperties` - 指定されたメンバー変数を持つオブジェクト（例：`object{foo:int, bar:string}`）。

### オブジェクト型

`TNamedObject` - オブジェクトの型が既知のオブジェクト型を表します（例：`Exception`、`Throwable`、`Foo\Bar`）。

`TGenericObject` - ジェネリックパラメータを持つオブジェクト型を表します（例：`ArrayObject<string, Foo\Bar>`）。

`TCallableObject` - `callable`でもあるオブジェクトを表します（つまり、`__invoke`が定義されています）。

`TAnonymousClassInstance` - 潜在的なメソッドを持つ匿名クラス（つまり`new class{}`）を表します。

### テンプレート

`TTemplateParam` - 以前に`@template`タグで指定されたテンプレートパラメータを表します。

`TTemplateParamClass` - 以前に`@template`タグで指定されたテンプレートパラメータに対応する`class-string`を表します。

## 型オブジェクトインスタンスの作成

与えられた型を記述するオブジェクトインスタンスを作成する方法は2つあります。直接newを使用して作成するか、docストリングから宣言的に作成することができます。通常、2番目のオプションを使用したいでしょう。しかし、このデータの構造を理解することは、プラグインに渡される型を理解するのに役立ちます。

これらのクラスは時々変更されることがあるので、`Type::parseString`は常により堅牢なオプションになります。

### 型オブジェクトインスタンスを直接作成する

次の例は、文字列、浮動小数点数、'Foo\Bar\SomeClass'というクラスを表す型を構築します。

```php
new TLiteralString('テキスト文字列')
new TLiteralFloat(3.142)
new TNamedObject('Foo\Bar\SomeClass')
```

Psalm内の型は、利便性のため常に共用体でラップされています。型が期待される場所（プロパティ型、戻り値型、引数型など）のほとんどで、共用体も使用できます。したがって、単一の原子型（TIntなど）を共用体コンテナでラップすることで、他の場所でその型を均一に扱うことができ、以下のような繰り返しのチェックを避けることができます：

```php
if ($type instanceof Union)
   foreach ($types->getTypes() as $atomic)
      handleAtomic($atomic);
else handleAtomic($type);

// 共用体コンテナを使用すると、以下のようになります
foreach ($types->getTypes() as $atomic)
   handleAtomic($atomic);
```

また、共用体ツリーは常に浅くなります。なぜなら、Psalmは共用体の共用体を単一レベルの共用体に平坦化するからです（`((A|B)|(C|D) => A|B|C|D)`）。

より複雑な型は以下のように構築できます。以下は3つのキーを持つ連想配列を表しています。Psalmはこれを'オブジェクトライク配列'と呼び、'TKeyedArray'クラスで表現します。

```php
new Union([
    new TKeyedArray([
        'key_1' => new Union([new TString()]),
        'key_2' => new Union([new TInt()]),
        'key_3' => new Union([new TBool()])])]);
```

Typeオブジェクトには、型を自動的に共用体でラップするいくつかの静的ヘルパーメソッドが含まれています。したがって、これはより簡潔に書くことができます：

```php
new Union([
    new Type\Atomic\TKeyedArray([
        'first' => Type::getInt(),
        'second' => Type::getString()])]);
```

また、`Type::getInt(5)`を使用して、リテラル整数値5に対応する共用体型を生成することもできます。

### docストリング型から型オブジェクトインスタンスを作成する

これらのインスタンスを作成するもう一つの方法は、静的メソッド`parseString`を含む`Psalm\Type`クラスを使用することです。任意のdocストリング型記述をこれに渡すと、対応するオブジェクト表現を返します。

```php
\Psalm\Type::parseString('int|null');
```

Psalmが与えられた型をオブジェクトとしてどのように表現するかを知るには、この関数への入力として型を指定し、結果に対して`var_dump`を呼び出すことで確認できます。

## 型の操作

型を操作するためのいくつかの一般的な操作があります：

### 型の結合

2つ以上の型を結合して新しい共用体型を作成するには：

```php
Type::combineUnionTypes($type1, $type2);
```

### 型の交差

2つ以上の型を交差させて新しい交差型を作成するには：

```php
Type::intersectUnionTypes($type1, $type2);
```

### 型の比較

2つの型が互換性があるかどうかを確認するには：

```php
TypeComparator::isContainedBy($codebase, $type1, $type2);
```

### 型の絞り込み

条件に基づいて型を絞り込むには：

```php
$newType = TypeNarrower::narrowToNotNull($type);
```

## プラグインでの型の使用

プラグインを開発する際、これらの型オブジェクトを使用して、コードの型情報を分析したり操作したりすることができます。例えば：

```php
public function afterExpressionAnalysis(
    Expr $expr,
    Context $context,
    StatementsSource $statements_source,
    Codebase $codebase,
    array &$file_replacements = []
): ?bool {
    if ($expr instanceof PhpParser\Node\Expr\Variable
        && is_string($expr->name)
    ) {
        $type = $statements_source->getNodeTypeProvider()->getType($expr);
        if ($type instanceof Union && $type->hasString()) {
            // 文字列型を含む変数に対して何かを行う
        }
    }
    return null;
}
```

この例では、変数式を検査し、その型に文字列が含まれているかどうかをチェックしています。

型システムを深く理解することで、Psalmプラグインの機能を最大限に活用し、より高度な静的解析を実装することができます。

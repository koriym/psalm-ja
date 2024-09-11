# プラグイン：型システムの内部

Psalmの型システムは、プログラム内の変数の型を異なるクラスを使用して表現します。プラグインはこの型情報を受け取り、更新することができます。

## ユニオン型

使用する可能性のあるすべての型情報は、[ユニオン型](../../annotating_code/type_syntax/union_types.md)でラップされます。

`Union`クラスのコンストラクタは`Atomic`型の配列を受け取り、これらの型の1つ以上を同時に表現できます。これらはdocコメントの縦棒に対応します。

```php
new Union([new TNamedObject('Foo\Bar\SomeClass')]); // docblockのFoo\Bar\SomeClassに相当
new Union([new TString(), new TInt()]); // docblockのstring|intに相当
```

## アトミック型

フロート、整数、文字列などのプリミティブ型に加えて、配列やクラスがあります。これらはすべて[`src/Psalm/Types/Atomic`](https://github.com/vimeo/psalm/tree/master/src/Psalm/Type/Atomic)にあります。

このフォルダ内のすべての非抽象クラスが有効な型であることに注意してください。それらはすべて'T'という接頭辞が付いています。

クラスは以下の通りです：

### その他

`TVoid` - `void`型を表し、通常は何も返さない関数/メソッドの注釈に使用されます
`TNull` - `null`型を表します
`TNever` - 例外を投げるか終了する（組み込みの`exit()`のような）関数のために、決して返らない`no-return`/`never-return`型を表します。また、可能な型を持たないユニオン型（不可能な交差など）にも使用されます。空の配列`[]`は`array<never, never>`型を持ちます。
`TMixed` - 式の型が不明な場合に使用される`mixed`型を表します
`TNonEmptyMixed` - 上記と同様ですが、空ではありません。`$x`が外部で`mixed`の場合、`if ($x) {...}`文の中の`$x`に対して生成されます。
`TEmptyMixed` - 上記と同様ですが、空です。`$x`が外部で`mixed`の場合、`if (!$x) {...}`文の中の`$x`に対して生成されます。
`TIterable` - [`iterable`型](https://www.php.net/manual/en/language.types.iterable.php)を表します（`is_iterable`チェックの結果でもあります）。
`TResource` - `resource`型を表します（例：ファイルハンドル）。
`TClosedResource` - 閉じられた`resource`型を表します（例：`fclose()`を通じたファイルハンドル）。
`TAssertionFalsy` - ブールコンテキストで計算した時に偽に還元される任意の値を表します。これはアサーションに使用されます。
`TConditional` - phpdocにおける条件付き戻り値型の内部表現です。例：（$param1 is int ? int : string）
`TIntMask` - そのパラメータのビットマスク組み合わせの結果である型を表します。`int-mask<1, 2, 4>`は`1|2|3|4|5|6|7`に対応します。
`TIntMaskOf` - 上記と同様ですが、コード内の定数への参照で使用されます。`int-mask-of<MyClass::CLASS_CONSTANT_*>`は、3つの定数1、2、4がある場合、`1|2|3|4|5|6|7`に対応します。
`TKeyOf` - 配列のオフセットを表します（例：`key-of<MyClass::CLASS_CONSTANT>`）。
`TValueOf` - 配列または列挙型の値を表します（例：`value-of<MyClass::CLASS_CONSTANT>`）。
`TTemplateIndexedAccess` - ドキュメント化予定
`TTemplateKeyOf` - 配列の型がテンプレートの場合にTKeyOfを使用する際の型を表します
`TTemplateValueOf` - 配列または列挙型の型がテンプレートの場合にTValueOfを使用する際の型を表します
`TPropertiesOf` - クラスのプロパティとその型をキー付き配列として表します（例：`properties-of<MyClass>`）
`TTemplatePropertiesOf` - クラスの型がテンプレートの場合にTPropertiesOfを使用する際の型を表します
`TTypeAlias` - ドキュメント化予定

### スカラースーパータイプ

`TScalar` - `scalar`スーパータイプを表します（`is_scalar`チェックの結果でもあります）。この型は`float`、`int`、`bool`、`string`を包含します。
`TEmptyScalar` - 空でもある`scalar`型を表します。
`TNonEmptyScalar` - 空でない`scalar`型を表します。

### 数値スーパータイプ

`TNumeric` - `numeric`型を表します（`is_numeric`チェックの結果でもあります）。
`TEmptyNumeric` - 空でもある`numeric`型を表します（`is_numeric`と`empty`チェックの結果でもあります）。

### スカラー型

すべてのスカラー型にはリテラルバージョンがあります（例：`int`対`int(5)`）。

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
`TFalse` - `false`値型を表します
`TTrue` - `true`値型を表します

```php
/** @return string|false
    文字列が空の場合はfalse、それ以外はパラメータの最初の文字を返す */
function firstChar(string $s) { return empty($s) ? false : $s[0]; }
```

ここでは、関数が真を返すことはありませんが、falseをboolに置き換えなければならない場合、Psalmは真を可能な戻り値として考慮しなければなりません。より狭い型を使用することで、以下のような無意味なコードを報告できます（https://psalm.dev/r/037291351d）：

```php
$first = firstChar("sdf");
if (true === $first) {
  echo "これは実際にデッドコードです";
}
```

#### 文字列

`TString` - 正確な値が不明な`string`型を表します。
`TNonEmptyString` - 空でない文字列を表します
`TNumericString` - 数値でもある文字列を表します（例：`"5"`）。`is_string($s) && is_numeric($s)`の結果になることがあります。
`TLiteralString` - 値が既知の文字列を表すために使用されます。
`TClassString` - 有効なPHPクラスを表す文字列を記述するために使用される`class-string`型を表します。クラスが継承する親タイプはコンストラクタで指定されるかもしれません。
`TLiteralClassString` - 特定のクラス文字列を表します。`A::class`のような式から生成されます。
`TTraitString` - 有効なPHPトレイトを表す文字列を記述するために使用される`trait-string`型を表します。
`TDependentGetClass` - get_class($var)で見つかる完全修飾クラスである文字列値を表します
`TDependentGetDebugType` - get_debug_type($var)で見つかる型の文字列値を表します
`TDependentGetType` - gettype($var)で見つかる型の文字列値を表します
`TCallableString` - `callable-string`型を表します。これは`callable`でもある未知の文字列を表すために使用されます。
`TSqlSelectString` - これは特別な型で、プラグインによる消費のために特別に用意されています。
`TLowercaseString` - すべての文字が小文字の文字列を表します（`strtolower`呼び出しの結果でもあります）。
`TNonEmptyLowercaseString` - すべての文字が小文字の空でない文字列を表します（`strtolower`呼び出しの結果でもあります）。
`TSingleLetter` - 長さが1の文字列を表します

#### スカラークラス定数

`TScalarClassConstant` - 値がまだ知られていない可能性のあるクラス定数を表します。

#### 配列キースーパータイプ

`TArrayKey` - `array-key`型を表します。これは`array`のオフセットになり得るものに使用されます。

### 配列

`TArray` - `array<TKey, TValue>`形式の単純な配列を表します。両方がユニオン型である2つの要素を持つ配列を期待します。
`TNonEmptyArray` - 上記と同様ですが、空でないことが分かっている配列を表します。
`TKeyedArray` は'オブジェクトライクな配列'を表します - 既知のキーを持つ配列です。

```php
$x = ["a" => 1, "b" => 2]; // TKeyedArray、array{a: int, b: int}です
$y = rand(0, 1) ? ["a" => null] : ["a" => 1, "b" => "b"]; // オプションのキー/値を持つTKeyedArray、array{a: ?int, b?: string}です
```

この型は（現在非推奨の`TList`型の代わりに）リストを表すためにも使用されます。すべての連想配列がオブジェクトライクとみなされるわけではないことに注意してください。キーが不明な場合、配列は2つの型間のマッピングとして扱われます。

```php
$a = [];
foreach (range(1,1) as $_) $a[(string)rand(0,1)] = rand(0,1); // array<string,int>
```

`TCallableKeyedArray` - `callable`でもあるオブジェクトライクな配列を表します。
`TClassStringMap` - 各値の型がその文字列キー値の関数である配列を表します

### 呼び出し可能とクロージャ

`TCallable` - `callable`型を表します。`is_callable`チェックの結果になることがあります。
`TClosure` - `Closure`型を表します。

`TCallable`と`TClosure`はオプションでパラメータと戻り値の型も定義できます。

### オブジェクトスーパータイプ

`TObject` - `object`型を表します
`TObjectWithProperties` - 指定されたメンバー変数を持つオブジェクトを表します。例：`object{foo:int, bar:string}`。

### オブジェクト型

`TNamedObject` - オブジェクトの型が既知のオブジェクト型を表します。例：`Exception`、`Throwable`、`Foo\Bar`
`TGenericObject` - ジェネリックパラメータを持つオブジェクト型を表します。例：`ArrayObject<string, Foo\Bar>`
`TCallableObject` - `callable`でもあるオブジェクトを表します（つまり、`__invoke`が定義されています）。
`TAnonymousClassInstance` - 潜在的なメソッドを持つ匿名クラス（つまり、`new class{}`）を表します

### テンプレート

`TTemplateParam` - 以前に`@template`タグで指定されたテンプレートパラメータを表します。
`TTemplateParamClass` - 以前に`@template`タグで指定されたテンプレートパラメータに対応する`class-string`を表します。

## 型オブジェクトインスタンスの作成

与えられた型を記述するオブジェクトインスタンスを作成する方法は2つあります。直接newを使用して作成するか、docstringから宣言的に作成することができます。通常は2番目のオプションを使用したいでしょう。しかし、このデータの構造を理解することで、プラグインに渡される型を理解するのに役立ちます。

これらのクラスは時々変更されることがあるため、`Type::parseString`は常により堅牢なオプションになります。

### 型オブジェクトインスタンスを直接作成する

次の例は、文字列、浮動小数点数、'Foo\Bar\SomeClass'というクラスを表す型を構築します。

```php
new TLiteralString('テキスト文字列')
new TLiteralFloat(3.142)
new TNamedObject('Foo\Bar\SomeClass')
```

Psalm内の型は常に利便性の機能としてユニオンでラップされます。型を期待するほぼどこでもユニオンを取得できます（プロパティ型、戻り値型、引数型など）。そのため、単一のアトミック型（TIntなど）をユニオンコンテナでラップすることで、以下のような繰り返しのチェックなしに、その型を統一的に扱うことができます：

```php
if ($type instanceof Union)
   foreach ($types->getTypes() as $atomic)
      handleAtomic($atomic);
else handleAtomic($type);

// ユニオンコンテナを使用すると以下のようになります
foreach ($types->getTypes() as $atomic)
   handleAtomic($atomic);
```

また、ユニオンツリーは常に浅くなります。Psalmはユニオンのユニオンを単一レベルのユニオンに平坦化します（`((A|B)|(C|D) => A|B|C|D)`）。

より複雑な型は以下のように構築できます。以下は3つのキーを持つ連想配列を表しています。Psalmはこれらを'オブジェクトライクな配列'と呼び、'TKeyedArray'クラスで表現します。

```php
        new Union([
            new TKeyedArray([
                'key_1' => new Union([new TString()]),
                'key_2' => new Union([new TInt()]),
                'key_3' => new Union
([new TBool()])])]);
```

Typeオブジェクトには、型を自動的にUnionでラップするいくつかの静的ヘルパーメソッドが含まれています。したがって、これはより簡潔に書くことができます：

```php
new Union([
    new Type\Atomic\TKeyedArray([
        'first' => Type::getInt(),
        'second' => Type::getString()])]);
```

また、`Type::getInt(5)`を使用して、リテラルint値5に対応するユニオン型を生成することもできます。

### docstring型から型オブジェクトインスタンスを作成する

型のオブジェクト表現を作成するもう一つの方法は、静的メソッド`parseString`を含む`Psalm\Type`クラスを使用することです。このメソッドにはどのようなdocstring型の説明でも渡すことができ、対応するオブジェクト表現を返します。

```php
\Psalm\Type::parseString('int|null');
```

Psalmが特定の型をオブジェクトとしてどのように表現するかを知るには、この関数への入力として型を指定し、結果に対して`var_dump`を呼び出すことができます。

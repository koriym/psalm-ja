# プラグイン：システム内部のタイプ

Psalmの型システムは、異なるクラスを使ってプログラム内の変数の型を表します。プラグインはこの型情報を受け取り、更新することができます。

## ユニオン型

使用する可能性のあるすべての型情報は、[Union Type](../../annotating_code/type_syntax/union_types.md) でラップされます。

`Union` クラスのコンストラクタは、`Atomic` 型の配列を受け取り、一度に1つ以上の型を表すことができる。これらはdocコメントの縦棒に対応します。

``` php
new Union([new TNamedObject('Foo\\Bar\\SomeClass')]); // equivalent to Foo\Bar\SomeClass in docblock
new Union([new TString(), new TInt()]); // equivalent to string|int in docblock
```

## 原子型

浮動小数点数、整数、文字列などのプリミティブ型、配列、クラス。これらはすべて[`src/Psalm/Types/Atomic`](https://github.com/vimeo/psalm/tree/master/src/Psalm/Type/Atomic) にある。

このフォルダ内の抽象クラスでないものはすべて有効な型であることに注意してください。これらはすべて接頭辞に'T'がついています。

クラスは以下の通りです：

### Misc

`TVoid` - は`void` 型を表します。通常は、何も返さない関数/メソッドをアノテートするために使用します。

`TNull` - は`null` 型を表す。

`TNever` - は`no-return`/`never-return` 型を表します。これは、例外をスローするか、あるいは（組み込み関数`exit()` のように）終了して、決して返らない関数のためのものです。また、可能性のない型（例えば不可能な交点）を持つことができない和集合型にも使用されます。  空の配列`[]` は `array<never, never>`.

`TMixed` `mixed` - 式の型がわからない場合に使用する。

`TNonEmptyMixed `- 上記と同様ですが、空ではありません。`if` ステートメント`if ($x) {...}` の内部`$x` に対して、`$x` が外部`mixed` の場合に生成されます。

`TEmptyMixed` - 上記と同じですが、空です。`if` `$x` `mixed` ステートメント の内部 に対して生成される。

 - は、 （ のチェックの結果であることもある）を表す。

 - は 型（ファイルハンドルなど）を表す。

 - は、クローズされた 型を示す(例: を介したファイルハンドル)。

 - ブーリアン文脈で計算されたときに false に還元される値を表します。これはアサーションに使用される

 - phpdoc における、条件付き返り値の型の内部表現。たとえば ($param1 is int ? int : string) のようになります。

 - パラメータをビットマスクで組み合わせた結果の型を表します。`if (!$x) {...}` `$x``TIterable` [`iterable` type](https://www.php.net/manual/en/language.types.iterable.php) `is_iterable``TResource` `resource``TClosedResource` `resource` `fclose()``TAssertionFalsy``TConditional``TIntMask` `int-mask<1, 2, 4>`に対応します。`1|2|3|4|5|6|7`

`TIntMaskOf` - と同じですが、コード中の定数への参照で使われます。 `int-mask-of<MyClass::CLASS_CONSTANT_*>`1,2,4の3つの定数がある場合、`1|2|3|4|5|6|7` 。

`TKeyOf` - 配列のオフセットを表す。 `key-of<MyClass::CLASS_CONSTANT>`).

`TValueOf` - 配列または列挙型の値を表す (例 `value-of<MyClass::CLASS_CONSTANT>`).

`TTemplateIndexedAccess` - ドキュメント化する

`TTemplateKeyOf` - 配列の型がテンプレートである場合に TKeyOf を使用する際に使用される型を表します。

`TTemplateValueOf` - 配列または列挙型の型がテンプレートの場合に TValueOf を使用する際に使用される型を表します。

`TPropertiesOf` - クラスのプロパティとその型をキー付き配列として表します (例 `properties-of<MyClass>`)

`TTemplatePropertiesOf` - クラスの型がテンプレートの場合に TPropertiesOf を使用する際に使用される型を表します。

`TTypeAlias` - ドキュメント化予定

### スカラー・スーパータイプ

`TScalar` - は、`scalar` のスーパータイプ（`is_scalar` のチェックの結果にもなる）を表します。この型は`float` 、`int` 、`bool` 、`string` を包含する。

`TEmptyScalar` - は空でもある`scalar` 型を示す。

`TNonEmptyScalar` - も空でない`scalar` 型を表す。

### 数値型スーパータイプ

`TNumeric` - は、`numeric` 型を表します（`is_numeric` 検査の結果にもなり得ます）。

`TEmptyNumeric` - は空でもある`numeric` 型を表します (`is_numeric` と`empty` のチェックの結果でもあり得ます)。

### スカラー型

`int` `int(5)`すべてのスカラー型にはリテラル・バージョンがある。

#### Ints

`TInt` - は`int` 型を表し、正確な値は不明である。

`TLiteralInt` - は、正確な数値が既知の整数値を表すために使用されます。

`TIntRange` - を使用すると、境界値を持つ int 値を表すことができます (例. `int<1, 5>`).

#### 浮動小数点数

`TFloat` - は`float` 型を表し、正確な値は不明です。

`TLiteralFloat` - は、正確な数値が既知である浮動小数点値を表すために使用されます。

#### ブール

`TBool`,`TFalse` 、`TTrue`

`TBool` - は、正確な値が不明な`bool` 型を表す。

`TFalse` - は`false` の値型を表す。

`TTrue` - は`true` の値型を示す。

``` php
/** @return string|false    false when string is empty, first char of the parameter otherwise */ function firstChar(string $s) { return empty($s) ? false : $s[0]; }
```

ここで、関数は決してtrueを返さないかもしれませんが、もしfalseをboolに置き換えなければならないとしたら、Psalmはtrueを戻り値として考慮しなければならないでしょう。より狭い型では、このような無意味なコードを報告することができる（https://psalm.dev/r/037291351d）：

``` php
$first = firstChar("sdf"); if (true === $first) {   echo "This is actually dead code"; }
```

#### 文字列

`TString` - は`string` 、正確な値は不明。

`TNonEmptyString` - も空でない文字列を表します。

`TNumericString` - は数値でもある文字列を表す。例えば`"5"` 。これは、`is_string($s) && is_numeric($s)` 。

`TLiteralString` - は、値が既知の文字列を表すのに使われます。

`TClassString` - は`class-string` 型を表します。これは、有効な PHP クラスを表す文字列を表すために使用します。コンストラクタで、クラスの元になる親型を指定することもできますし、 しないこともできます。

`TLiteralClassString` - は特定のクラス文字列を表し、`A::class` のような式で生成されます。

`TTraitString` - は`trait-string` 型を表し、有効な PHP 特性を表す文字列を記述します。

`TDependentGetClass` - get_class($var) で見つかった完全修飾クラスを値とする文字列を表します。

`TDependentGetDebugType` - get_debug_type($var) で見つかった型を表す文字列を表します。

`TDependentGetType` - gettype($var) で見つかった型を表す文字列を返します。

`TCallableString` - は`callable-string` 型を表します。未知の文字列を表すために使われ、`callable` でもあります。

`TSqlSelectString` - これは特別な型であり、特にプラグインによって使用されます。

`TLowercaseString` - は、すべての文字が小文字である文字列を表します。(これは`strtolower` 呼び出しの結果でもあり得る)。

`TNonEmptyLowercaseString` - 空でない文字列を表し、すべての文字が小文字になります。(これは、`strtolower` 呼び出しの結果にもなる)。

`TSingleLetter` - は長さ1の文字列を表す。

#### スカラー・クラス定数

`TScalarClassConstant` - は、まだ値がわからないかもしれないクラス定数を表します。

#### 配列キーのスーパータイプ

`TArrayKey` - は`array-key` 型を表します。`array` のオフセットになるようなものに使われます。

### 配列

`TArray` - は単純な配列を表す。 `array<TKey, TValue>`.これは、2つの要素を持つ配列を想定しており、両方ともユニオン型です。

`TNonEmptyArray` - と同じですが、空でないことが分かっている配列を表します。

`TKeyedArray` オブジェクトのような配列」、つまり既知のキーを持つ配列を表します。

``` php
$x = ["a" => 1, "b" => 2]; // is TKeyedArray, array{a: int, b: int} $y = rand(0, 1) ? ["a" => null] : ["a" => 1, "b" => "b"]; // is TKeyedArray with optional keys/values, array{a: ?int, b?: string}
```

この型は、（現在は非推奨となっている`TList` 型の代わりに）リストを表すのにも使われる。  

すべての連想配列がオブジェクト型とみなされるわけではないことに注意。キーがわからない場合、配列は2つの型のマッピングとして扱われる。

``` php
$a = []; foreach (range(1,1) as $_) $a[(string)rand(0,1)] = rand(0,1); // array<string,int>
```

`TCallableKeyedArray` - は、_also_`callable` であるオブジェクトのような配列を表す。

`TClassStringMap` - 各値の型が、その文字列キー値の関数である配列を表します。

### callable とクロージャ

`TCallable` - は`callable` 型を表します。`is_callable` 。
`TClosure` - は`Closure` タイプを示す。

`TCallable` と`TClosure` は、オプションでパラメータと戻り値の型も定義できる。

### オブジェクトのスーパータイプ

`TObject` - は`object` 型を表す。

`TObjectWithProperties` - 指定されたメンバ変数を持つオブジェクト 例：`object{foo:int, bar:string}`.

### オブジェクトの型

`TNamedObject` - オブジェクトの型が既知の場合、オブジェクトの型を示す 例：`Exception`,`Throwable` 、`Foo\Bar`

`TGenericObject` - は一般的なパラメータを持つオブジェクト・タイプを表す。 `ArrayObject<string, Foo\Bar>`

`TCallableObject` - は、`callable` （すなわち、`__invoke` が定義されている）オブジェクトを表す。

`TAnonymousClassInstance` - 潜在的なメソッドを持つ無名クラス（すなわち`new class{}` ）を表す。

### テンプレート

`TTemplateParam` - は、`@template` タグであらかじめ指定されているテンプレート・パラメーターを表します。

`TTemplateParamClass` - は、`@template` タグで以前に指定されたテンプレート・パラメータに対応する`class-string` を示す。

## タイプオブジェクトのインスタンスの生成

与えられた型を記述するオブジェクト・インスタンスを作成する方法は2つある。newを使って直接作成する方法と、doc文字列から宣言的に作成する方法です。通常は2番目の方法を使うことになるだろう。しかし、このデータの構造を理解することは、プラグインに渡される型を理解するのに役立ちます。

これらのクラスは時々変更されるので、`Type::parseString` 。

### 型オブジェクトのインスタンスを直接作成する

以下の例では、文字列、浮動小数点数、および「FooBar」というクラスを表す型を構 成している。

``` php
new TLiteralString('A text string')
new TLiteralFloat(3.142)
new TNamedObject('Foo\Bar\SomeClass')
```

便利な機能として、Psalm内の型は常にユニオンに包まれる。型があると思われるほとんどの場所で、ユニオンを得ることができる（プロパティ型、戻り値型、引数型など）。そのため、単一のアトミック型（TIntなど）をユニオン・コンテナでラップすることで、このようなチェックを繰り返すことなく、他の場所でもその型を統一的に扱うことができます：

``` php
if ($type instanceof Union)
   foreach ($types->getTypes() as $atomic)
      handleAtomic($atomic);
else handleAtomic($type);

// with union container it becomes
foreach ($types->getTypes() as $atomic)
   handleAtomic($atomic);
```

また、Psalmはユニオンのユニオンを単一レベルのユニオンに平坦化するので、ユニオンツリーは常に浅い`((A|B)|(C|D) => A|B|C|D)` 。

より複雑な型は以下のように構築できる。以下は3つのキーを持つ連想配列を表しています。Psalmはこれを'オブジェクトのような配列'と呼び、'TKeyedArray'クラスで表します。


``` php
        new Union([
            new TKeyedArray([
                'key_1' => new Union([new TString()]),
                'key_2' => new Union([new TInt()]),
                'key_3' => new Union([new TBool()])])]);
```

Typeオブジェクトにはいくつかの静的ヘルパー・メソッドが含まれており、それらは自動的に型をUnionでラップする。従って、これはより簡潔に書くことができる：

``` php
new Union([
    new Type\Atomic\TKeyedArray([
        'first' => Type::getInt(),
        'second' => Type::getString()])]);
```

`Type::getInt(5)` 、int型のリテラル値5に対応するユニオン型を生成することもできる。


### doc文字列型から型オブジェクトのインスタンスを生成する

これらのインスタンスを生成するもう1つの方法は、`parseString` という静的メソッドを含むクラス`Psalm\Type` を使うことです。このメソッドに任意のdoc文字列型の説明を渡すことができ、対応するオブジェクト表現を返します。

``` php
\Psalm\Type::parseString('int|null');
```

Psalmが与えられた型をどのようにオブジェクトとして表現するかは、この関数への入力として型を指定し、その結果に対して`var_dump` 。

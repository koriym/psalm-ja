# サポートされるdocblock注釈

Psalmは様々なdocblockアノテーションをサポートしています。

## PHPDoc タグ

Psalm は、あなたのコードを理解するために以下の PHPDoc タグを使います：

-[`@var`](https://docs.phpdoc.org/latest/guide/references/phpdoc/tags/var.html) プロパティや変数の型を指定するのに使用します -[`@return`](https://docs.phpdoc.org/latest/guide/references/phpdoc/tags/return.html) 関数やメソッド、クロージャの戻り値の型を指定するのに使用します -[`@param`](https://docs.phpdoc.org/latest/guide/references/phpdoc/tags/param.html) 関数やメソッド、クロージャに渡されるパラメータの型を指定するのに使用します -  や を使用しているオブジェクトでアクセスできるプロパティを指定するのに使用します、[`@property`](https://docs.phpdoc.org/latest/guide/references/phpdoc/tags/property.html) `__get` および`__set` を使用するオブジェクトでアクセスできるプロパティを指定するのに使用します -[`@property-read`](https://docs.phpdoc.org/latest/guide/references/phpdoc/tags/property.html) `__get` を使用するオブジェクトで読み込めるプロパティを指定するのに使用します -[`@property-write`](https://docs.phpdoc.org/latest/guide/references/phpdoc/tags/property.html) `__set` を使用するオブジェクトで書き込めるプロパティを指定するのに使用します -[`@method`](https://docs.phpdoc.org/latest/guide/references/phpdoc/tags/method.html) `__call` を使用するオブジェクトで使用できるマジックメソッドを指定するのに使用します。-[`@deprecated`](https://docs.phpdoc.org/latest/guide/references/phpdoc/tags/deprecated.html) 関数、メソッド、クラス、インタフェースを非推奨としてマークするのに使用します -[`@internal`](https://docs.phpdoc.org/latest/guide/references/phpdoc/tags/internal.html) アプリケーションやライブラリの内部にあるクラス、関数、プロパティをマークするのに使用します。-[`@mixin`](#mixins) 現在のクラスが参照されているクラスのメソッドとプロパティをプロキシしていることを Psalm に伝えるために使用します。

###`@var` タグのラベル外使用法

`@var` タグはプロパティにのみ使用されます。Psalm は、PHPStorm や他の静的解析ツールに習って、`@var Type [VariableReference]` という形式でインラインで使用することができます。

`VariableReference` が指定された場合は、`$variable` または`$variable->property` の形式でなければなりません。代入の上で使用された場合、Psalm は`VariableReference` が代入される変数と一致するかどうかをチェックします。両者が異なる場合、Psalmは`Type` を`VariableReference` に代入し、それを以下の式で使用します。

`VariableReference` が指定されない場合、Psalm は、式の右辺が代入であれ戻り値であれ、`Type` 型であることを注釈で知らせます。

```php
<?php
/** @var string */
$a = $_GET['foo'];

/** @var string $b */
$b = $_GET['bar'];

function bat(): string {
    /** @var string */
    return $_GET['bat'];
}
```
### @mixins

クラスのdocblockに`@mixin` を追加すると、クラス・プロキシが参照されたクラスのメソッドとプロパティをプロキシすることをPsalmに伝えます。

```php
class A
{
    public string $a = 'A';
 
    public function doA(): void
    {
    }
}

/**
 * @mixin A
 */
class B
{
    public string $b = 'B';

    public function doB(): void
    {
    }

    public function __call($name, $arguments)
    {
        (new A())->$name(...$arguments);
    }
    
    public function __get($name)
    {
        (new A())->$name;
    }
}

$b = new B();
$b->doB();
$b->doA(); // works
echo $b->b;
echo $b->a; // works
```


## Psalm固有のタグ

Psalmがあなたのコードをどのように扱うかを決めるカスタムタグがいくつかあります。

###`@psalm-consistent-constructor`

参照[UnsafeInstantiation](../running_psalm/issues/UnsafeInstantiation.md)

###`@psalm-consistent-templates`

参照[UnsafeGenericInstantiation](../running_psalm/issues/UnsafeGenericInstantiation.md)

###`@param-out` 、`@psalm-param-out`

by-ref型が入力されたものと異なることを指定するために使用します。以下の関数では、最初のパラメータを NULL にすることができますが、関数が実行されると、バイ 参照値は NULL ではなくなります。

```php
<?php
/**
 * @param-out string $s
 */
function addFoo(?string &$s) : void {
    if ($s === null) {
        $s = "hello";
    }
    $s .= "foo";
}
```

###`@psalm-var`,`@psalm-param`,`@psalm-return`,`@psalm-property`,`@psalm-property-read`,`@psalm-property-write` 、`@psalm-method`

phpDocumentor がサポートしていない形式 ([but supported by Psalm](#type-syntax)) で型を指定する場合は、PHPDoc タグの前に`@psalm-` を追加すると IDE が混乱せずに済むでしょう。`@psalm`-prefixed タグが指定された場合、Psalm は prefixed でないタグの代わりにそれを使用します。

###`@psalm-ignore-var`

このアノテーションは、同じdocblockに書かれた`@var` アノテーションを無視するために使用されます。IDEの中には、ジェネリックスのような複雑な型を完全に理解していないものがあります。そのようなIDEのオートコンプリートを利用するために、psalmがうまく型を推測できる場合でも、明示的な`@var` アノテーションを使用したい場合があります。これは、明示的な`@var` アノテーションがpsalmによって推測された型を上書きしてしまうため、多くの場合、型チェックの効果を弱めてしまいます。psalmは`@psalm-ignore-var` と同位置にある`@var` アノテーションを無視するため、IDEは`@var` で指定された型を自動補完に使用することができます。

```php
<?php
/** @return iterable<array-key,\DateTime> $f */
function getTimes(int $n): iterable {
    while ($n--) {
        yield new \DateTime();
    }
};
/**
 * @var \Datetime[] $times
 * @psalm-ignore-var
 */
$times = getTimes(3);
// this trace shows "iterable<array-key, DateTime>" instead of "array<array-key, Datetime>"
/** @psalm-trace $times */
foreach ($times as $time) {
    echo $time->format('Y-m-d H:i:s.u') . PHP_EOL;
}
```

###`@psalm-suppress SomeIssueName`

このアノテーションは、問題を抑制するために使用されます。これは、関数docblock、クラスdocblock、およびインラインで使用でき、以下のステートメントに適用されます。

関数docblockの例：

```php
<?php
/**
 * @psalm-suppress PossiblyNullOperand
 */
function addString(?string $s) {
    echo "hello " . $s;
}
```

インラインの例

```php
<?php
function addString(?string $s) {
    /** @psalm-suppress PossiblyNullOperand */
    echo "hello " . $s;
}
```

`@psalm-suppress all` を使用すると、issueを個別にリストアップする代わりに、すべてのissueを抑制することができます。

###`@psalm-assert`,`@psalm-assert-if-true`,`@psalm-assert-if-false`,`@psalm-if-this-is` および`@psalm-this-out`

[Adding assertions](adding_assertions.md) を参照。

###`@psalm-ignore-nullable-return`

これはPsalmに関数/メソッドがNULLを返しても気にしないように指示するために使います。ちょっとしたハックですが、NULLでない値の確信度が非常に高い場合や、他の関数がその特定のコードパスに対してNULLでない値を保証している場合に便利です。

```php
<?php class Foo {} function takesFoo(Foo $f): void {}

/** @psalm-ignore-nullable-return */ function getFoo(): ?Foo {   return rand(0, 10000) > 1 ? new Foo() : null; }

takesFoo(getFoo());
```

###`@psalm-ignore-falsable-return`

これは`false` 。Psalmは、`preg_replace` のような、与えられた入力にエンコードエラーがある場合にfalseを返すことができる関数に内部的にこれを使用していますが、99.9%の場合、関数は期待通りに動作します。

###`@psalm-seal-properties`,`@psalm-no-seal-properties`,`@seal-properties` 、`@no-seal-properties`

マジック・プロパティ・ゲッター/セッターがある場合、`@psalm-seal-properties` を使って、`@property` （または`@property-read`/`@property-write` ）アノテーションのリストに含まれていないプロパティの取得と設定を禁止するように Psalm に指示することができます。
これは設定オプション`sealAllProperties` で自動的に有効になります。`@psalm-no-seal-properties`

```php
<?php /**  * @property string $foo  * @seal-properties  */ class A {      public function __get(string $name): ?string {           if ($name === "foo") {                return "hello";           }      }

     public function __set(string $name, $value): void {} }

$a = new A(); $a->bar = 5; // this call fails
```

###`@psalm-seal-methods`,`@psalm-no-seal-methods`,`@seal-methods` 、`@no-seal-methods`

マジック・メソッド呼び出しがある場合、`@psalm-seal-methods` を使って、`@method` アノテーションのリストに含まれていないメソッドを呼び出さないように Psalm に指示することができます。
これは設定オプション`sealAllMethods` で自動的に有効になります。`@psalm-no-seal-methods`

```php
<?php /**  * @method foo(): string  * @seal-methods  */ class A {      public function __call(string $name, array $args) {           if ($name === "foo") {                return "hello";           }      }  }

$a = new A(); $b = $a->bar(); // this call fails
```

###`@psalm-internal`

クラス、プロパティ、または関数を、指定された名前空間の内部としてマークするために使用します。Psalm はこれを
PHPDoc の`@internal` タグとは若干異なります。`@internal` の場合、呼び出し元のコードが呼び出し元の名前空間とはまったく関係のない名前空間にある場合に問題が発生します。
すなわち、名前空間の最初の要素を共有していない場合です。

対照的に、`@psalm-internal` 、docblock行は名前空間を指定しなければならない。呼び出し元のコード
が指定された名前空間内にない場合、問題が発生します。

```php
<?php namespace A\B {     /**      * @internal      * @psalm-internal A\B      */     class Foo { } }

namespace A\B\C {     class Bat {         public function batBat(): void {             $a = new \A\B\Foo();  // this is fine         }     } }

namespace A\C {     class Bat {         public function batBat(): void {             $a = new \A\B\Foo();  // error         }     } }
```

###`@psalm-readonly` および`@readonly`

定義するクラスのコンストラクタ内でのみ書き込み可能なプロパティをアノテートするために使用します。

```php
<?php class B {   /** @readonly */   public string $s;

  public function __construct(string $s) {     $this->s = $s;   } }

$b = new B("hello"); echo $b->s; $b->s = "boo"; // disallowed
```

###`@psalm-mutation-free`

内部的にも、クラスのスコープの外部的にも、状態を変更しないクラス・メソッドをアノテートするために使用します。
これは、返り値がインスタンスのプロパティにのみ依存することを必要とします。例えば、`random_int` は
は乱数ジェネレータの内部状態を変化させるので、ここで変異させるとみなされます。

```php
<?php class D {   private string $s;

  public function __construct(string $s) {     $this->s = $s;   }

  /**    * @psalm-mutation-free    */   public function getShort() : string {     return substr($this->s, 0, 5);   }

  /**    * @psalm-mutation-free    */   public function getShortMutating() : string {     $this->s .= "hello"; // this is a bug     return substr($this->s, 0, 5);   } }
```

###`@psalm-external-mutation-free`

クラスのスコープの外部で状態を変更しないクラス・メソッドをアノテートするために使用します。

```php
<?php class E {   private string $s;

  public function __construct(string $s) {     $this->s = $s;   }

  /**    * @psalm-external-mutation-free    */   public function getShortMutating() : string {     $this->s .= "hello"; // this is fine     return substr($this->s, 0, 5);   }

  /**    * @psalm-external-mutation-free    */   public function save() : void {     file_put_contents("foo.txt", $this->s); // this is a bug   } }
```

###`@psalm-immutable`

すべてのプロパティがコンシューマによって`@psalm-readonly` として扱われ、すべてのインスタンスメソッドが`@psalm-mutation-free` として扱われるクラスをアノテートするために使用されます。

```php
<?php /**  * @psalm-immutable  */ abstract class Foo {     public string $baz;

    abstract public function bar(): int; }

/**  * @psalm-immutable  */ final class ChildClass extends Foo {     public function __construct(string $baz)     {         $this->baz = $baz;     }

    public function bar(): int     {         return 0;     } }

$anonymous = new /** @psalm-immutable */ class extends Foo {     public string $baz = "B";

    public function bar(): int     {         return 1;     } };
```

###`@psalm-pure`

[pure function](https://en.wikipedia.org/wiki/Pure_function) 、出力が入力の関数であることを示す。

```php
<?php class Arithmetic {   /** @psalm-pure */   public static function add(int $left, int $right) : int {     return $left + $right;   }

  /** @psalm-pure - this is wrong */   public static function addCumulative(int $left) : int {     /** @var int */     static $i = 0; // this is a side effect, and thus a bug     $i += $left;     return $i;   } }

echo Arithmetic::add(40, 2); echo Arithmetic::add(40, 2); // same value is emitted

echo Arithmetic::addCumulative(3); // outputs 3 echo Arithmetic::addCumulative(3); // outputs 6
```

一方、`pure-callable` は、純粋である必要がある callable を示すのに使われます。

```php
/**  * @param pure-callable(mixed): int $callback  */ function foo(callable $callback) {...}

// this fails since random_int is not pure foo(     /** @param mixed $p */     fn($p) => random_int(1, 2) );
```

###`@psalm-allow-private-mutation`

プライベート・コンテキストで変更可能な読み取り専用プロパティをアノテートするために使用します。これを使用すると、パブリック・プロパティは別のクラスから読み取ることができますが、自身のクラスのメソッド内でのみ変異させることができます。

```php
<?php class Counter {   /**    * @readonly    * @psalm-allow-private-mutation    */   public int $count = 0;

  public function increment() : void {     $this->count++;   } }

$counter = new Counter(); echo $counter->count; // outputs 0 $counter->increment(); // Method can mutate property echo $counter->count; // outputs 1 $counter->count = 5; // This will fail, as it's mutating a property directly
```

###`@psalm-readonly-allow-private-mutation`

これは、プロパティ注釈`@readonly` と`@psalm-allow-private-mutation` の省略形です。

```php
<?php class Counter {   /**    * @psalm-readonly-allow-private-mutation    */   public int $count = 0;

  public function increment() : void {     $this->count++;   } }

$counter = new Counter(); echo $counter->count; // outputs 0 $counter->increment(); // Method can mutate property echo $counter->count; // outputs 1 $counter->count = 5; // This will fail, as it's mutating a property directly
```

###`@psalm-trace`

このアノテーションを使用すると、推測される型をトレースすることができます（*next* ステートメントに適用されます）。

```php
<?php

/** @psalm-trace $username */ $username = $_GET['username']; // prints something like "test.php:4 $username: mixed"

```

*注意*: これは[special low-level issue](../running_psalm/issues/Trace.md) をスローします。
これを見るには、グローバル`errorLevel` を 1 に設定するか、Psalm を
`--show-info=true`でPsalmを起動することもできますが、どちらも大量の
が出力される。もう1つの解決策は、issueのエラー・レベルを選択的に上げることです：

```xml
<!-- psalm.xml -->
<issueHandlers>
  <Trace errorLevel="error"/>
</issueHandlers>
```

###`@psalm-check-type`

このアノテーションを使用すると、推論された型が期待したものと一致することを確認できます。

```php
<?php

/** @psalm-check-type $foo = int */
$foo = 1; // No issue

/** @psalm-check-type $bar = int */
$bar = "not-an-int"; // Checked variable $bar = int does not match $bar = 'not-an-int'
```

###`@psalm-check-type-exact`

`@psalm-check-type` と同様だが、サブタイプを許さず、変数の正確な型をチェックする。

```php
<?php

/** @psalm-check-type-exact $foo = int */
$foo = 1; // Checked variable $foo = int does not match $foo = 1
```

###`@psalm-taint-*`

[Security Analysis annotations](../security_analysis/annotations.md) を参照。

###`@psalm-type`

これにより、別の型のエイリアスを定義できる。

```php
<?php
/**
 * @psalm-type PhoneType = array{phone: string}
 */
class Phone {
    /**
     * @psalm-return PhoneType
     */
    public function toArray(): array {
        return ["phone" => "Nokia"];
    }
}
```

###`@psalm-import-type`

このアノテーションを使用すると、[`@psalm-type`](#psalm-type) で定義された型をインポートできます。

```php
<?php
/**
 * @psalm-import-type PhoneType from Phone
 */
class User {
    /**
     * @psalm-return PhoneType
     */
    public function toArray(): array {
        return array_merge([], (new Phone())->toArray());
    }
}
```

また、型をインポートする際にエイリアスを付けることもできます：

```php
<?php
/**
 * @psalm-import-type PhoneType from Phone as MyPhoneTypeAlias
 */
class User {
    /**
     * @psalm-return MyPhoneTypeAlias
     */
    public function toArray(): array {
        return array_merge([], (new Phone())->toArray());
    }
}
```

###`@psalm-require-extends`

`@psalm-require-extends` アノテーションを使用すると、特質が使用クラスに課す要件を定義できます。

```php
<?php
abstract class DatabaseModel {
  // methods, properties, etc.
}

/**
 * @psalm-require-extends DatabaseModel
 */
trait SoftDeletingTrait {
  // useful but scoped functionality, that depends on methods/properties from DatabaseModel
}


class MyModel extends DatabaseModel {
  // valid
  use SoftDeletingTrait;
}

class NormalClass {
  // triggers an error
  use SoftDeletingTrait;
}
```

###`@psalm-require-implements`

`@psalm-require-extends` と同じ動作をするが、インターフェイスの場合。

###`@no-named-arguments`

これは、名前付きパラメータでタグ付けされた関数やメソッドへのアクセスを (`NamedArgumentNotAllowed` issueを発行して)阻止します。

ちなみに、以下のコードでは推論される型が変更されます：
```php
<?php
    function a(int ...$a){
        var_dump($a);
    }
```
`$a` の型は `array<array-key, int>``@no-named-arguments` 。 `list<int>`これは、オフセットがパラメータ名の文字列になる場合を除外するためです。

###`@psalm-ignore-variable-property` と`@psalm-ignore-variable-method`

Psalmがデッドコードを探すときに、変数プロパティ取得/変数メソッド呼び出しを無視するように指示します。
```php
class Foo
{
    // this property can be deleted by Psalter,
    // as potential reference in get() is ignored
    public string $bar = 'bar';

    public function get(string $name): mixed
    {
        /** @psalm-ignore-variable-property */
        return $this->{$name};
    }
}
```
Psalm が変数プロパティに遭遇すると、指定されたクラス内のすべてのプロパティを参照される可能性が あるものとして扱います。`@psalm-ignore-variable-property` アノテーションを使用すると、この参照は無視されます。

どちらの場合も`PossiblyUnusedProperty` が発行されますが、`@psalm-ignore-variable-property` を使用すると、[Psalter](../manipulating_code/fixing.md) が`Foo::$bar` を削除できるようになります。

`@psalm-ignore-variable-method` も同じように振る舞いますが、変数メソッド呼び出しの場合です。

###`@psalm-yield`

アノテーションされたオブジェクト・インスタンスが生成されるときに、ジェネレーターに送り返される値のタイプを指定するために使用します。

```php
<?php /**  * @template-covariant TValue  * @psalm-yield TValue  */ interface Promise {}

/**  * @template-covariant TValue  * @template-implements Promise<TValue>  */ class Success implements Promise {     /**      * @psalm-param TValue $value      */     public function __construct($value) {} }

/**  * @return Promise<string>  */ function fetch(): Promise {     return new Success('{"data":[]}'); }

function (): Generator {     $data = yield fetch();
    
    // this is fine, Psalm knows that $data is a string     return json_decode($data); };
```
このアノテーションは一般的な型のみをサポートします。つまり、`@psalm-yield string` などは無視されます。

###`@api` 、`@psalm-api`

クラスやメソッドが使用されていることを Psalm に伝えるために使用します。
クラスやメソッドが使用されていることをPalmに伝えるために使用されます。未使用の問題は抑制されます。

例えば、フレームワークでは、コントローラはしばしば「魔法のように」呼び出されます。
で呼び出されることがよくあります。このようなクラスには
`@psalm-api`.
```php
/**
 * @psalm-api
 */
class UnreferencedClass {}
```

###`@psalm-inheritors`

あるクラスが特定のサブセットによってのみ拡張できることを Psalm に伝えるために使用します。

例えば 
```php
<?php
/**
 * @psalm-inheritors FooClass|BarClass
 */
class BaseClass {}
class FooClass extends BaseClass {}
class BarClass extends BaseClass {}
class BazClass extends BaseClass {} // this is an error
```

## 型の構文

Psalm は PHPDoc の[type syntax](https://docs.phpdoc.org/latest/guide/guides/types.html) と[proposed PHPDoc PSR type syntax](https://github.com/php-fig/fig-standards/blob/master/proposed/phpdoc.md#appendix-a-types) をサポートしています。

詳しい説明は[Typing in Psalm](typing_in_psalm.md)

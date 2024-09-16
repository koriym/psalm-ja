# サポートされているdocblockアノテーション

Psalmは幅広いdocblockアノテーションをサポートしています。

## PHPDocタグ

Psalmはコードを理解するために以下のPHPDocタグを使用します：

- [`@var`](https://docs.phpdoc.org/latest/guide/references/phpdoc/tags/var.html)
  プロパティと変数の型を指定するために使用されます
- [`@return`](https://docs.phpdoc.org/latest/guide/references/phpdoc/tags/return.html)
  関数、メソッド、クロージャの戻り値の型を指定するために使用されます
- [`@param`](https://docs.phpdoc.org/latest/guide/references/phpdoc/tags/param.html)
  関数、メソッド、クロージャに渡されるパラメータの型を指定するために使用されます
- [`@property`](https://docs.phpdoc.org/latest/guide/references/phpdoc/tags/property.html)
  `__get`と`__set`を使用するオブジェクトでアクセス可能なプロパティを指定するために使用されます
- [`@property-read`](https://docs.phpdoc.org/latest/guide/references/phpdoc/tags/property.html)
  `__get`を使用するオブジェクトで読み取り可能なプロパティを指定するために使用されます
- [`@property-write`](https://docs.phpdoc.org/latest/guide/references/phpdoc/tags/property.html)
  `__set`を使用するオブジェクトで書き込み可能なプロパティを指定するために使用されます
- [`@method`](https://docs.phpdoc.org/latest/guide/references/phpdoc/tags/method.html)
  `__call`を使用するオブジェクトで利用可能なマジックメソッドを指定するために使用されます
- [`@deprecated`](https://docs.phpdoc.org/latest/guide/references/phpdoc/tags/deprecated.html)
  関数、メソッド、クラス、インターフェースが非推奨であることをマークするために使用されます
- [`@internal`](https://docs.phpdoc.org/latest/guide/references/phpdoc/tags/internal.html)
  アプリケーションやライブラリ内部のクラス、関数、プロパティをマークするために使用されます
- [`@mixin`](#mixins)
  現在のクラスが参照されたクラスのメソッドとプロパティをプロキシすることをPsalmに伝えるために使用されます

### `@var`タグの非標準的な使用

`@var`タグは本来プロパティにのみ使用されるべきです。Psalmは、PHPStormやその他の静的解析ツールに倣って、`@var Type [VariableReference]`の形式でインラインでの使用を許可しています。

`VariableReference`が提供される場合、それは`$variable`または`$variable->property`の形式である必要があります。代入の上で使用される場合、Psalmは`VariableReference`が代入される変数と一致するかどうかをチェックします。それらが異なる場合、Psalmは`Type`を`VariableReference`に割り当て、下の式で使用します。

`VariableReference`が与えられない場合、このアノテーションは式の右側（代入であれ戻り値であれ）が`Type`型であることをPsalmに伝えます。

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

クラスのdocblockに`@mixin`を追加すると、そのクラスが参照されたクラスのメソッドとプロパティをプロキシすることをPsalmに伝えます。

```php
class A {
    public string $a = 'A';
    
    public function doA(): void
    {
    }
}

/** 
 * @mixin A 
 */
class B {
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
        return (new A())->$name;
    }
}

$b = new B();
$b->doB();
$b->doA(); // 動作します
echo $b->b;
echo $b->a; // 動作します
```

## Psalm固有のタグ

コードをPsalmがどのように扱うかを決定する多くのカスタムタグがあります。

### `@psalm-consistent-constructor`

[UnsafeInstantiation](../running_psalm/issues/UnsafeInstantiation.md)を参照してください。

### `@psalm-consistent-templates`

[UnsafeGenericInstantiation](../running_psalm/issues/UnsafeGenericInstantiation.md)を参照してください。

### `@param-out`, `@psalm-param-out`

これは、参照渡しの型が入力時と異なることを指定するために使用されます。以下の関数では、最初のパラメータはnullである可能性がありますが、関数が実行された後、参照渡しの値はnullではありません。

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

### `@psalm-var`, `@psalm-param`, `@psalm-return`, `@psalm-property`, `@psalm-property-read`, `@psalm-property-write`, `@psalm-method`

phpDocumentorでサポートされていない形式（[ただしPsalmでサポートされている](#type-syntax)）で型を指定する場合、IDEを混乱させないように`@psalm-`をPHPDocタグの前に付けることができます。`@psalm-`プレフィックス付きのタグが与えられた場合、Psalmはそれをプレフィックスなしの対応するタグの代わりに使用します。

### `@psalm-ignore-var`

このアノテーションは、同じdocblockに書かれた`@var`アノテーションを無視するために使用されます。一部のIDEは、ジェネリクスのような複雑な型を完全には理解しません。そのようなIDEの自動補完を活用するために、Psalmが型を適切に推論できる場合でも、明示的な`@var`アノテーションを使用したい場合があります。多くの場合、明示的な`@var`アノテーションがPsalmの推論した型をオーバーライドするため、これは型チェックの効果を弱めます。Psalmは`@psalm-ignore-var`と共存する`@var`アノテーションを無視するため、IDEは自動補完のために`@var`で指定された型を使用でき、Psalmは型チェックのために独自の推論した型を使用できます。

```php
<?php
/** @return iterable<array-key,\DateTime> */
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
// このトレースは "array<array-key, Datetime>" ではなく "iterable<array-key, DateTime>" を表示します
/** @psalm-trace $times */
foreach ($times as $time) {
    echo $time->format('Y-m-d H:i:s.u') . PHP_EOL;
}
```

### `@psalm-suppress SomeIssueName`

このアノテーションは問題を抑制するために使用されます。関数のdocblock、クラスのdocblock、そしてインラインでも使用でき、次の文に適用されます。

関数のdocblockの例：

```php
<?php
/** 
 * @psalm-suppress PossiblyNullOperand 
 */
function addString(?string $s) {
    echo "hello " . $s;
}
```

インラインの例：

```php
<?php
function addString(?string $s) {
    /** @psalm-suppress PossiblyNullOperand */
    echo "hello " . $s;
}
```

`@psalm-suppress all`を使用して、個別にリストアップする代わりにすべての問題を抑制することができます。

### `@psalm-assert`, `@psalm-assert-if-true`, `@psalm-assert-if-false`, `@psalm-if-this-is` および `@psalm-this-out`

[アサーションの追加](adding_assertions.md)を参照してください。

### `@psalm-ignore-nullable-return`

これは、関数/メソッドがnullを返すことについてPsalmに心配させないように使用できます。少し問題がありますが、非nullの値に対して非常に高い確信度がある場合や、他の関数がその特定のコードパスで非nullの値を保証している場合に時々便利です。

```php
<?php
class Foo {}

function takesFoo(Foo $f): void {}

/** @psalm-ignore-nullable-return */
function getFoo(): ?Foo {
  return rand(0, 10000) > 1 ? new Foo() : null;
}

takesFoo(getFoo());
```

### `@psalm-ignore-falsable-return`

これは`false`に対して同じことを提供しますが、Psalmは内部的にこれを`preg_replace`のような関数に使用しています。これらの関数は、与えられた入力にエンコーディングエラーがある場合にfalseを返す可能性がありますが、99.9%の場合、関数は期待通りに動作します。

### `@psalm-seal-properties`, `@psalm-no-seal-properties`, `@seal-properties`, `@no-seal-properties`

マジックプロパティゲッター/セッターがある場合、`@psalm-seal-properties`を使用して、`@property`（または`@property-read`/`@property-write`）アノテーションのリストに含まれていないプロパティの取得と設定を禁止するようPsalmに指示できます。

これは設定オプション`sealAllProperties`で自動的に有効になり、`@psalm-no-seal-properties`を使ってクラスに対して無効にできます。

```php
<?php
/** 
 * @property string $foo
 * @seal-properties 
 */
class A {
    public function __get(string $name): ?string {
        if ($name === "foo") {
            return "hello";
        }
    }
    
    public function __set(string $name, $value): void {}
}

$a = new A();
$a->bar = 5; // この呼び出しは失敗します
```

### `@psalm-seal-methods`, `@psalm-no-seal-methods`, `@seal-methods`, `@no-seal-methods`

マジックメソッド呼び出し機能がある場合、`@psalm-seal-methods`を使用して、`@method`アノテーションのリストに含まれていないメソッドの呼び出しを禁止するようPsalmに指示できます。

これは設定オプション`sealAllMethods`で自動的に有効になり、`@psalm-no-seal-methods`を使ってクラスに対して無効にできます。

```php
<?php
/** 
 * @method foo(): string
 * @seal-methods 
 */
class A {
    public function __call(string $name, array $args) {
        if ($name === "foo") {
            return "hello";
        }
    }
}

$a = new A();
$b = $a->bar(); // この呼び出しは失敗します
```

### `@psalm-internal`

クラス、プロパティ、または関数を特定の名前空間に内部的なものとしてマークするために使用されます。PsalmはこれをPHPDocの`@internal`タグとは少し異なる方法で扱います。`@internal`の場合、呼び出しコードが呼び出しコードの名前空間と完全に無関係な名前空間にある場合（つまり、名前空間の最初の要素を共有していない場合）に問題が発生します。

対照的に、`@psalm-internal`の場合、docblockの行で名前空間を指定する必要があります。呼び出しコードが指定された名前空間内にない場合に問題が発生します。

```php
<?php
namespace A\B {
    /**
     * @internal
     * @psalm-internal A\B
     */
    class Foo { }
}

namespace A\B\C {
    class Bat {
        public function batBat(): void {
            $a = new \A\B\Foo();  // これは問題ありません
        }
    }
}

namespace A\C {
    class Bat {
        public function batBat(): void {
            $a = new \A\B\Foo();  // エラー
        }
    }
}
```

### `@psalm-readonly` および `@readonly`

定義クラスのコンストラクタでのみ書き込み可能なプロパティにアノテーションを付けるために使用されます。

```php
<?php
class B {
  /** @readonly */
  public string $s;

  public function __construct(string $s) {
    $this->s = $s;
  }
}

$b = new B("hello");
echo $b->s;
$b->s = "boo"; // 許可されません
```

### `@psalm-mutation-free`

クラスのスコープ内外で状態を変更しないクラスメソッドにアノテーションを付けるために使用されます。
これは、戻り値がインスタンスのプロパティにのみ依存することを要求します。例えば、`random_int`は乱数生成器の内部状態を変更するため、ここでは変更とみなされます。

```php
<?php
class D {
  private string $s;

  public function __construct(string $s) {
    $this->s = $s;
  }

  /**
   * @psalm-mutation-free
   */
  public function getShort() : string {
    return substr($this->s, 0, 5);
  }

  /**
   * @psalm-mutation-free
   */
  public function getShortMutating() : string {
    $this->s .= "hello"; // これはバグです
    return substr($this->s, 0, 5);
  }
}
```

### `@psalm-external-mutation-free`

クラスのスコープ外で状態を変更しないクラスメソッドにアノテーションを付けるために使用されます。

```php
<?php
class E {
  private string $s;

  public function __construct(string $s) {
    $this->s = $s;
  }

  /**
   * @psalm-external-mutation-free
   */
  public function getShortMutating() : string {
    $this->s .= "hello"; // これは問題ありません
    return substr($this->s, 0, 5);
  }

  /**
   * @psalm-external-mutation-free
   */
  public function save() : void {
    file_put_contents("foo.txt", $this->s); // これはバグです
  }
}
```

### `@psalm-immutable`

すべてのプロパティが消費者によって`@psalm-readonly`として扱われ、すべてのインスタンスメソッドが`@psalm-mutation-free`として扱われるクラスにアノテーションを付けるために使用されます。

```php
<?php
/** 
 * @psalm-immutable 
 */
abstract class Foo {
    public string $baz;
    abstract public function bar(): int;
}

/** 
 * @psalm-immutable 
 */
final class ChildClass extends Foo {
    public function __construct(string $baz)
    {
        $this->baz = $baz;
    }

    public function bar(): int
    {
        return 0;
    }
}

$anonymous = new /** @psalm-immutable */ class extends Foo {
    public string $baz = "B";

    public function bar(): int
    {
        return 1;
    }
};
```

### `@psalm-pure`

[純粋関数](https://en.wikipedia.org/wiki/Pure_function) - その出力が入力のみの関数であるもの - にアノテーションを付けるために使用されます。

```php
<?php
class Arithmetic {
  /** @psalm-pure */
  public static function add(int $left, int $right) : int {
    return $left + $right;
  }

  /** @psalm-pure - これは間違っています */
  public static function addCumulative(int $left) : int {
    /** @var int */
    static $i = 0; // これは副作用であり、したがってバグです
    $i += $left;
    return $i;
  }
}

echo Arithmetic::add(40, 2);
echo Arithmetic::add(40, 2); // 同じ値が出力されます
echo Arithmetic::addCumulative(3); // 3を出力します
echo Arithmetic::addCumulative(3); // 6を出力します
```

一方、`pure-callable`は純粋である必要があるコールバックを示すために使用できます。

```php
/** 
 * @param pure-callable(mixed): int $callback 
 */
function foo(callable $callback) {...}

// これは失敗します。random_intは純粋ではないため
foo(
    /** @param mixed $p */
    fn($p) => random_int(1, 2)
);
```

### `@psalm-allow-private-mutation`

privateコンテキストで変更可能な読み取り専用プロパティにアノテーションを付けるために使用されます。これにより、publicプロパティは別のクラスから読み取ることができますが、自身のクラスのメソッド内でのみ変更できます。

```php
<?php
class Counter {
  /**
   * @readonly
   * @psalm-allow-private-mutation
   */
  public int $count = 0;

  public function increment() : void {
    $this->count++;
  }
}

$counter = new Counter();
echo $counter->count; // 0を出力します
$counter->increment(); // メソッドはプロパティを変更できます
echo $counter->count; // 1を出力します
$counter->count = 5; // これは失敗します。プロパティを直接変更しているため
```

### `@psalm-readonly-allow-private-mutation`

これは、プロパティアノテーション`@readonly`と`@psalm-allow-private-mutation`の省略形です。

```php
<?php
class Counter {
  /**
   * @psalm-readonly-allow-private-mutation
   */
  public int $count = 0;

  public function increment() : void {
    $this->count++;
  }
}

$counter = new Counter();
echo $counter->count; // 0を出力します
$counter->increment(); // メソッドはプロパティを変更できます
echo $counter->count; // 1を出力します
$counter->count = 5; // これは失敗します。プロパティを直接変更しているため
```

### `@psalm-trace`

このアノテーションを使用して、推論された型をトレースできます（*次の*文に適用されます）。

```php
<?php
/** @psalm-trace $username */
$username = $_GET['username']; // "test.php:4 $username: mixed" のようなものを出力します
```

*注意*: これは[特別な低レベルの問題](../running_psalm/issues/Trace.md)をスローします。
これを見るには、グローバルの`errorLevel`を1に設定するか、Psalmを`--show-info=true`で呼び出すことができますが、どちらの解決策も多くの出力を生成する可能性があります。もう一つの解決策は、問題のエラーレベルを選択的に上げて、1つだけ余分なエラーを得ることです：

```xml
<!-- psalm.xml -->
<issueHandlers>
  <Trace errorLevel="error"/>
</issueHandlers>
```

### `@psalm-check-type`

このアノテーションを使用して、推論された型が期待するものと一致することを確認できます。

```php
<?php
/** @psalm-check-type $foo = int */
$foo = 1; // 問題なし

/** @psalm-check-type $bar = int */
$bar = "not-an-int"; // チェックされた変数 $bar = int は $bar = 'not-an-int' と一致しません
```

### `@psalm-check-type-exact`

`@psalm-check-type`と同様ですが、サブタイプを許可せずに変数の正確な型をチェックします。

```php
<?php
/** @psalm-check-type-exact $foo = int */
$foo = 1; // チェックされた変数 $foo = int は $foo = 1 と一致しません
```

### `@psalm-taint-*`

[セキュリティ分析アノテーション](../security_analysis/annotations.md)を参照してください。

### `@psalm-type`

これを使用して、別の型のエイリアスを定義できます。

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

### `@psalm-import-type`

このアノテーションを使用して、[`@psalm-type`](#psalm-type)で定義された型を、それが他の場所で定義されている場合にインポートできます。

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

インポート時に型にエイリアスを付けることもできます：

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

### `@psalm-require-extends`

`@psalm-require-extends`アノテーションを使用すると、トレイトが使用クラスに課す要件を定義できます。

```php
<?php
abstract class DatabaseModel {
  // メソッド、プロパティなど
}

/** 
 * @psalm-require-extends DatabaseModel 
 */
trait SoftDeletingTrait {
  // 有用だが、スコープが限定された機能。DatabaseModelのメソッド/プロパティに依存しています
}

class MyModel extends DatabaseModel {
  // 有効
  use SoftDeletingTrait;
}

class NormalClass {
  // エラーをトリガーします
  use SoftDeletingTrait;
}
```

### `@psalm-require-implements`

`@psalm-require-extends`と同じように動作しますが、インターフェースに対してです。

### `@no-named-arguments`

これは、名前付きパラメータを使用して関数やメソッドにアクセスすることを防ぎます（`NamedArgumentNotAllowed`問題を発生させます）。
偶然にも、以下のコードの推論される型が変更されます：

```php
<?php
    function a(int ...$a){
        var_dump($a);
    }
```

`$a`の型は`@no-named-arguments`がない場合は`array<array-key, int>`ですが、それがある場合は`list<int>`になります。これは、パラメータ名を持つ文字列オフセットのケースを除外するためです。

### `@psalm-ignore-variable-property` および `@psalm-ignore-variable-method`

デッドコードを探す際に変数プロパティフェッチ / 変数メソッド呼び出しを無視するようPsalmに指示します。

```php
class Foo {
    // このプロパティはPsalterによって削除される可能性があります
    // get()内の潜在的な参照は無視されるため
    public string $bar = 'bar';

    public function get(string $name): mixed
    {
        /** @psalm-ignore-variable-property */
        return $this->{$name};
    }
}
```

Psalmが変数プロパティに遭遇した場合、与えられたクラス内のすべてのプロパティが潜在的に参照されているものとして扱います。
`@psalm-ignore-variable-property`アノテーションを使用すると、この参照は無視されます。
両方のケースで`PossiblyUnusedProperty`が発生しますが、`@psalm-ignore-variable-property`を使用すると、[Psalter](../manipulating_code/fixing.md)が`Foo::$bar`を削除できるようになります。

`@psalm-ignore-variable-method`は同じように動作しますが、変数メソッド呼び出しに対してです。

### `@psalm-yield`

アノテーションが付けられたオブジェクトインスタンスがyieldされたときに、ジェネレータに送り返される値の型を指定するために使用されます。

```php
<?php
/** 
 * @template-covariant TValue
 * @psalm-yield TValue
 */
interface Promise {}

/** 
 * @template-covariant TValue
 * @template-implements Promise<TValue>
 */
class Success implements Promise {
    /**
     * @psalm-param TValue $value
     */
    public function __construct($value) {}
}

/** 
 * @return Promise<string>
 */
function fetch(): Promise {
    return new Success('{"data":[]}');
}

function (): Generator {
    $data = yield fetch();
    
    // これは問題ありません。Psalmは$dataが文字列であることを知っています
    return json_decode($data);
};
```

このアノテーションはジェネリック型のみをサポートしています。つまり、例えば`@psalm-yield string`は無視されます。

### `@api`, `@psalm-api`

クラスやメソッドが使用されていることをPsalmに伝えるために使用されます。これは、コード内に明示的な参照が見つからない場合でも使用されます。未使用の問題は抑制されます。

例えば、フレームワークでは、コントローラはしばしば「魔法のように」呼び出され、コード内に明示的な参照がありません。これらのクラスには`@psalm-api`をマークする必要があります。

```php
/** 
 * @psalm-api 
 */
class UnreferencedClass {}
```

### `@psalm-inheritors`

クラスが特定のサブセットのクラスによってのみ拡張できることをPsalmに伝えるために使用されます。

例えば、

```php
<?php
/** 
 * @psalm-inheritors FooClass|BarClass 
 */
class BaseClass {}

class FooClass extends BaseClass {}
class BarClass extends BaseClass {}
class BazClass extends BaseClass {} // これはエラーです
```

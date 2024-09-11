# テンプレート化されたアノテーション

Docblockを使用すると、コードの動作に関する簡単な情報をPsalmに伝えることができます。例えば、関数の戻り値の型で `@return int` を使用すると、その関数が `int` を返すべきであることをPsalmに伝えます。同様に、`@return MyContainer` は、関数がユーザー定義クラス `MyContainer` のインスタンスを返すべきであることを示します。いずれの場合も、Psalmは関数が実際にそれらの型を返しているかどうかをチェックし、さらにその関数を呼び出すコードが返される値を適切に使用しているかどうかもチェックします。

テンプレート化された型を使用すると、コードの動作についてさらに多くの情報をPsalmに伝えることができます。

簡単なクラス `MyContainer` を見てみましょう：

```php
<?php
class MyContainer {
  private $value;
  public function __construct($value) {
    $this->value = $value;
  }
  public function getValue() {
    return $this->value;
  }
}
```

Psalmが `$my_container->getValue()` の戻り値の型を処理する際、値が任意のものになる可能性があるため、何が取得されるかわかりません。

テンプレート化されたアノテーションは、この問題の解決策を提供します。`MyContainer` 内の値のプレースホルダーとして、ジェネリック/テンプレート化されたパラメータ `T` を定義できます：

```php
<?php
/** 
 * @template T 
 */
class MyContainer {
  /** @var T */
  private $value;
  
  /** @param T $value */
  public function __construct($value) {
    $this->value = $value;
  }
  
  /** @return T */
  public function getValue() {
    return $this->value;
  }
}
```

これで、docblockで `MyContainer` を参照する際に、そのテンプレート化されたパラメータに値を代入できます。例えば、`@return MyContainer<int>` と記述することで、Psalmはその戻り値の型を評価する際に `T` を `int` に置き換えます。これは実質的に次のようなクラスとして扱われます：

```php
<?php
class One_off_instance_of_MyContainer {
  /** @var int */
  private $value;
  
  /** @param int $value */
  public function __construct($value) {
    $this->value = $value;
  }
  
  /** @return int */
  public function getValue() {
    return $this->value;
  }
}
```

このパターンは、モッキング、コレクション、イテレータ、任意のオブジェクトのロードなど、さまざまな状況で使用できます。Psalmには、コードベースでテンプレート化された型を簡単に使用できるようにするための多くのアノテーションがあります。

## `@template`, `@psalm-template`

`@template`/`@psalm-template` タグを使用すると、クラスや関数がジェネリック型パラメータを宣言できます。

非常に簡単な例として、この関数は渡されたものをそのまま返します：

```php
<?php
/** 
 * @template T
 * @psalm-param T $t
 * @return T
 */
function mirror($t) {
    return $t;
}

$a = 5;
$b = mirror($a); // Psalmは結果がintであることを知っています

$c = "foo";
$d = mirror($c); // Psalmは結果がstringであることを知っています
```

Psalmは、PHP配列関数のスタブ版でも `@template` アノテーションを使用しています。例えば：

```php
<?php
/** 
 * キーを持つ1つの配列と値を持つもう1つの配列を取り、それらを組み合わせます
 * 
 * @template TKey
 * @template TValue
 * 
 * @param array<mixed, TKey> $arr
 * @param array<mixed, TValue> $arr2
 * @return array<TKey, TValue>
 */
function array_combine(array $arr, array $arr2) {}
```

### 注意
- クラスのdocblockでは、`@template` タグの順序が重要です。これらはdocblockでそれらのジェネリックパラメータが参照される順序を決定します。
- テンプレート化された型の名前（例：`TKey`、`TValue`）は、それらが宣言されているクラスまたは関数のスコープ外では重要ではありません。

## @param class-string&lt;T&gt;

Psalmではクラス型もパラメータ化できます：

```php
<?php
/** 
 * @template T of Foo
 * @psalm-param class-string<T> $class
 * @return T
 */
function instantiator(string $class) {
    return new $class();
}

class Foo {
    public final function __construct() {}
}

class FooChild extends Foo {}

$r = instantiator(FooChild::class);
// Psalmは$rがFooChildタイプのオブジェクトであることを知っています
```

## テンプレートの継承

Psalmでは、`@extends`/`@template-extends` を使用してテンプレート化されたクラスを拡張できます：

```php
<?php
/** 
 * @template T 
 */
class ParentClass {}

/** 
 * @extends ParentClass<int> 
 */
class ChildClass extends ParentClass {}
```

同様に、`@implements`/`@template-implements` を使用してインターフェースを実装できます：

```php
<?php
/** 
 * @template T 
 */
interface IFoo {}

/** 
 * @implements IFoo<int> 
 */
class Foo implements IFoo {}
```

そして、`@use`/`@template-use` を使用してトレイトをインポートできます：

```php
<?php
/** 
 * @template T 
 */
trait MyTrait {}

class Foo {
    /**
     * @use MyTrait<int>
     */
    use MyTrait;
}
```

また、あるテンプレート化されたクラスを別のテンプレート化されたクラスで拡張することもできます：

```php
<?php
/** 
 * @template T1 
 */
class ParentClass {}

/** 
 * @template T2
 * @extends ParentClass<T2>
 */
class ChildClass extends ParentClass {}
```

## テンプレートの制約

入力を制限するために `@template of <type>` を使用できます。例えば、特定のクラスに制限するには次のように使用できます：

```php
<?php
class Foo {}
class FooChild extends Foo {}

/** 
 * @template T of Foo
 * @psalm-param T $t
 * @return array<int, T>
 */
function makeArray($t) {
    return [$t];
}

$a = makeArray(new Foo()); // array<int, Foo> と型付けされます
$b = makeArray(new FooChild()); // array<int, FooChild> と型付けされます
$c = makeArray(new stdClass()); // 型エラー
```

テンプレート化された型はキーと値のペアに限定されません。テンプレート対応の型の複数の引数にわたってテンプレートを再利用することもできます：

```php
<?php
/** 
 * @template T0 of array-key
 * 
 * @template-implements IteratorAggregate<T0, int>
 */
abstract class Foo implements IteratorAggregate {
  /**
   * @var int
   */
  protected $rand_min;
  
  /**
   * @var int
   */
  protected $rand_max;
  
  public function __construct(int $rand_min, int $rand_max) {
    $this->rand_min = $rand_min;
    $this->rand_max = $rand_max;
  }
  
  /**
   * @return Generator<T0, int, mixed, T0>
   */
  public function getIterator() : Generator {
    $j = random_int($this->rand_min, $this->rand_max);
    for($i = $this->rand_min; $i <= $j; $i += 1) {
      yield $this->getFuzzyType($i) => $i ** $i;
    }
    return $this->getFuzzyType($j);
  }
  
  /**
   * @return T0
   */
  abstract protected function getFuzzyType(int $i);
}

/** 
 * @template-extends Foo<int>
 */
class Bar extends Foo {
  protected function getFuzzyType(int $i) : int {
    return $i;
  }
}

/** 
 * @template-extends Foo<string>
 */
class Baz extends Foo {
  protected function getFuzzyType(int $i) : string {
    return static::class . '[' . $i . ']';
  }
}
```

## テンプレートの共変性

次のようなコードを想像してみてください：

```php
<?php
class Animal {}
class Dog extends Animal {}
class Cat extends Animal {}

/** 
 * @template T
 */
class Collection {
    /**
     * @var array<int, T>
     */
    public array $list;
    
    /**
     * @param array<int, T> $list
     */
    public function __construct(array $list) {
        $this->list = $list;
    }
    
    /**
     * @param T $t
     */
    public function add($t) : void {
        $this->list[] = $t;
    }
}

/** 
 * @param Collection<Animal> $collection 
 */
function addAnimal(Collection $collection) : void {
    $collection->add(new Cat());
}

/** 
 * @param Collection<Dog> $dog_collection
 */
function takesDogList(Collection $dog_collection) : void {
    addAnimal($dog_collection);
}
```

最後の呼び出し `addAnimal($dog_collection)` はコレクションの型を壊します - 突然、犬のコレクションが犬または猫のコレクションになってしまいます。これは良くありません。

これを防ぐために、Psalmは `addAnimal($dog_collection)` を呼び出すときに "addAnimal expects a `Collection<Animal>`, but `Collection<Dog>` was passed" というエラーを発します。もしこのルールに初めて遭遇したなら、おそらく混乱するでしょう - `Animal` を受け入れる関数は、その派生型も喜んで受け入れるはずです。しかし、上の例で見たように、そうすることで問題が発生する可能性があります。

しかし、テンプレートパラメータのサブタイプを渡しても完全に安全な場合もあります：

```php
<?php
abstract class Animal {
    abstract public function getNoise() : string;
}

class Dog extends Animal {
    public function getNoise() : string { return "woof"; }
}

class Cat extends Animal {
    public function getNoise() : string { return "miaow"; }
}

/** 
 * @template T
 */
class Collection {
    /** @var array<int, T> */
    public array $list = [];
}

/** 
 * @param Collection<Animal> $collection
 */
function getNoises(Collection $collection) : void {
    foreach ($collection->list as $animal) {
        echo $animal->getNoise();
    }
}

/** 
 * @param Collection<Dog> $dog_collection
 */
function takesDogList(Collection $dog_collection) : void {
    getNoises($dog_collection);
}
```

ここでは何も悪いことはしていません - 単にオブジェクトの配列を反復処理しているだけです。しかし、Psalmは依然として同じ基本的なエラーを出します - "getNoises expects a `Collection<Animal>`, but `Collection<Dog>` was passed"。

テンプレート化されたパラメータ `T` のサブタイプを渡しても安全であることをPsalmに伝えるには、`@template-covariant T`（または `@psalm-template-covariant T`）アノテーションを使用できます：

```php
<?php
/** 
 * @template-covariant T
 */
class Collection {
    /** @var array<int, T> */
    public array $list = [];
}
```

上の例でこれを行うとエラーは発生しません：[https://psalm.dev/r/5254af7a8b](https://psalm.dev/r/5254af7a8b)

しかし、`@template-covariant` はすべてのエラーを取り除くわけではありません - 最初の例にこれを追加すると、新しいエラーが発生します - [https://psalm.dev/r/0fcd699231](https://psalm.dev/r/0fcd699231) - 関数の入力に共変テンプレートパラメータを使用しようとしていることを警告します。これはよくありません。なぜなら、おそらくコレクションを何らかの方法で変更しようとしているからです（これは、再び、違反です）。

### 不変性についてはどうでしょうか？

Psalmには[関数的不変性を宣言するための包括的なサポート](https://psalm.dev/articles/immutability-and-beyond)があります。

クラスが不変であることを確認すれば、共変パラメータを入力として受け取る `add` メソッドを持つクラスを宣言できますが、コレクションをまったく変更せず、代わりに新しいコレクションを返すようにできます：

```php
<?php
/*** @template-covariant T
 * @psalm-immutable
 */
class Collection {
    /**
     * @var array<int, T>
     */
    public array $list = [];

    /**
     * @param array<int, T> $list
     */
    public function __construct(array $list) {
        $this->list = $list;
    }

    /**
     * @param T $t
     * @return Collection<T>
     */
    public function add($t) : Collection {
        return new Collection(array_merge($this->list, [$t]));
    }
}
```

これは完全に有効で、Psalmは警告を出しません。

## ビルトインのテンプレート化されたクラスとインターフェース

Psalmには、独自のコードで拡張/実装できる多くのビルトインクラスとインターフェースのサポートがあります。

- `interface Traversable<TKey, TValue>`
- `interface ArrayAccess<TKey, TValue>`
- `interface IteratorAggregate<TKey, TValue> extends Traversable<TKey, TValue>`
- `interface Iterator<TKey, TValue> extends Traversable<TKey, TValue>`
- `interface SeekableIterator<TKey, TValue> extends Iterator<TKey, TValue>`
- `class Generator<TKey, TValue, TSend, TReturn> extends Traversable<TKey, TValue>`
- `class ArrayObject<TKey, TValue> implements IteratorAggregate<TKey, TValue>, ArrayAccess<TKey, TValue>`
- `class ArrayIterator<TKey of array-key, TValue> implements SeekableIterator<TKey, TValue>, ArrayAccess<TKey, TValue>`
- `class DOMNodeList<TNode of DOMNode> implements Traversable<int, TNode>`
- `class SplDoublyLinkedList<TValue> implements Iterator<TKey, TValue>, ArrayAccess<TKey, TValue>`
- `class SplQueue<TValue> extends SplDoublyLinkedList<TValue>`
- `abstract class FilterIterator<TKey, TValue, TIterator>`

# テンプレート

Docblockを使うと、Psalmにコードの動作に関する簡単な情報を伝えることができます。例えば、関数の返り値の型で`@return int` を指定すると、関数は`int` を返すべきであると Psalm に伝え、`@return MyContainer` を指定すると、関数はユーザ定義クラスのインスタンス`MyContainer` を返すべきであると Psalm に伝えます。どちらの場合でも、Psalmはその関数が実際にそれらの型を返すかどうか、そしてその関数を呼び出すものがその戻り値を適切に使用するかどうかをチェックすることができます。

テンプレート化された型を使用すると、コードがどのように動作するかについて、さらに多くの情報をPsalmに伝えることができます。

単純なクラス`MyContainer` を見てみましょう：

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

Psalmが`$my_container->getValue()` のリターン・タイプを処理するとき、その値は任意のものである可能性があるため、何を取り出すのかわからない。

テンプレート化されたアノテーションは回避策を提供してくれます。`MyContainer` 内の値のプレースホルダとなるジェネリック/テンプレート化されたパラメータ`T` を定義することができます：

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

これで、docblockで`MyContainer` 。 `@return MyContainer<int>`.これはPsalmに、その戻り値の型を評価するときに、`T` を`int` に置き換えるように指示します。

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

このパターンは、モック、コレクション、イテレータ、任意のオブジェクトのロードなど、多くの異なる状況で使用できます。Psalmには、コードベースでテンプレート型を簡単に使えるようにするためのアノテーションが多数用意されています。

##`@template` 、`@psalm-template`

`@template`/`@psalm-template` タグは、クラスや関数が汎用型のパラメータを宣言できるようにします。

非常に単純な例として、この関数は渡されたものを何でも返します：

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
$b = mirror($a); // Psalm knows the result is an int

$c = "foo";
$d = mirror($c); // Psalm knows the result is string
```

Psalmは、PHPの配列関数のスタブ版でも`@template` アノテーションを使用しています。

```php
<?php
/**
 * Takes one array with keys and another with values and combines them
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

### 注釈 -`@template` タグの順番は、docblock の中で汎用パラメータを参照する順番を決めるため、クラスの docblock では重要です。- テンプレート化された型の名前(例:`TKey`,`TValue`)は、宣言されたクラスや関数の範囲外では重要ではありません。

## @param class-string&lt;T&gt;

Psalmでは、クラス型をパラメータ化することもできます。

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
// Psalm knows $r is an object of type FooChild
```

## テンプレート継承

Psalmは、`@extends`/`@template-extends` でテンプレート化されたクラスを拡張することができます：

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

同様に、`@implements`/ でインターフェイスを実装できる。`@template-implements`

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

で実装し、`@use`/`@template-use`

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

また、あるテンプレート・クラスを別のテンプレート・クラスで拡張することもできます。

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

## テンプレート制約

入力を制限するために `@template of <type>`を使って入力を制限することができます。例えば、特定のクラスに制限するには、次のようにします。

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
$a = makeArray(new Foo()); // typed as array<int, Foo>
$b = makeArray(new FooChild()); // typed as array<int, FooChild>
$c = makeArray(new stdClass()); // type error
```

テンプレート化された型はキーと値のペアに限定されず、テンプレートをサポートする型の複数の引数にわたってテンプレートを再利用することができます：
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

## テンプレート共分散

次のようなコードがあるとする：

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

最後の呼び出し`addAnimal($dog_collection)` 、コレクションの型が壊れてしまう。犬のコレクションが突然、犬_または_猫のコレクションになってしまうのだ。これは悪いことだ。

これを防ぐために、Psalmは`addAnimal($dog_collection)` 。 `Collection<Animal>`しかし `Collection<Dog>`が渡された」というエラーを出す。このルールに出会ったことがなければ、おそらく混乱するだろう。`Animal` を受け入れる関数は、そのサブタイプを喜んで受け入れるだろう。しかし、上の例で見たように、そうすると問題が発生する可能性がある。

しかし、テンプレート・パラメータのサブタイプを渡しても全く問題ない場合もあります：

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

ここでは何も悪いことをしているわけではなく、オブジェクトの配列を繰り返し処理しているだけだ。しかし、Psalmはまだ同じ基本的なエラー、"getNoises expects a `Collection<Animal>`しかし `Collection<Dog>`が渡された"。

`@template-covariant T` （または`@psalm-template-covariant T` ）というアノテーションを使うことで、`T` というテンプレート化されたパラメータにサブタイプを渡しても安全であることをPsalmに伝えることができます：

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

上記の例でこれを実行してもエラーは発生しません：[https://psalm.dev/r/5254af7a8b](https://psalm.dev/r/5254af7a8b)

しかし、`@template-covariant` 、すべてのエラーを取り除けるわけではありません。最初の例にこれを追加すると、[https://psalm.dev/r/0fcd699231](https://psalm.dev/r/0fcd699231) 、関数の入力に共変テンプレート・パラメーターを使おうとしているという新しいエラーが発生します。これは、コレクションを何らかの方法で変更している可能性が高い（これも違反です）ので、よくありません。

### しかし、不変性についてはどうでしょうか？

Psalmには[comprehensive support for declaring functional immutability](https://psalm.dev/articles/immutability-and-beyond) があります。

クラスが不変であることを確認すれば、`add` メソッドを持つクラスを宣言できます。このメソッドは、入力として共変パラメタを取りますが、コレクションをまったく変更せず、代わりに新しいものを返します：

```php
<?php
/**
 * @template-covariant T
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

これは完全に有効であり、Psalmは文句を言わない。

## 組み込みのテンプレートクラスとインターフェース

Psalmは多くの組み込みクラスとインターフェースをサポートしています。

- `interface Traversable<TKey, TValue>`- `interface ArrayAccess<TKey, TValue>`- `interface IteratorAggregate<TKey, TValue> extends Traversable<TKey, TValue>`- `interface Iterator<TKey, TValue> extends Traversable<TKey, TValue>`- `interface SeekableIterator<TKey, TValue> extends Iterator<TKey, TValue>`

- `class Generator<TKey, TValue, TSend, TReturn> extends Traversable<TKey, TValue>`- `class ArrayObject<TKey, TValue> implements IteratorAggregate<TKey, TValue>, ArrayAccess<TKey, TValue>`- `class ArrayIterator<TKey of array-key, TValue> implements SeekableIterator<TKey, TValue>, ArrayAccess<TKey, TValue>`- `class DOMNodeList<TNode of DOMNode> implements Traversable<int, TNode>`- `class SplDoublyLinkedList<TValue> implements Iterator<TKey, TValue>, ArrayAccess<TKey, TValue>`- `class SplQueue<TValue> extends SplDoublyLinkedList<TValue>`

- `abstract class FilterIterator<TKey, TValue, TIterator>`

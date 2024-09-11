# ユーティリティの種類

Psalm は、PHP の型システムに超能力をもたらすいくつかの _magical_ ユーティリティ型をサポートしています。

## key-of&lt;T&gt;

(Psalm 5.0+)

`key-of` ユーティリティは、任意の[array type](array_types.md) のオフセットタイプを返します。

いくつかの例を示します：

- `key-of<Foo\Bar::ARRAY_CONST>``ARRAY_CONST` のオフセット・タイプに評価されます（Psalm 3.3+）。 `key-of<list<mixed>>``int` と評価される `key-of<array{a: mixed, b: mixed}|array{c: mixed}>``'a'|'b'|'c'` と評価される `key-of<string[]>``array-key` に評価される `key-of<T>`テンプレート・パラメタのオフセット・タイプに評価される (`@template T of array` を確保)

### テンプレートの使用に関する注意事項

テンプレート・パラメータで`key-of` を使用する場合、これらの許可されたメソッドでのみ型チェックを行うことができます：

-`array_keys($t)` -`array_key_first($t)` -`array_key_last($t)`

現在のところ、`array_key_exists($key, $t)` は、`$key` が `key-of<T>`.

```php
/**
 * @template T of array
 * @param T $array
 * @return list<key-of<T>>
 */
function getKeys($array) {
    return array_keys($array);
}
```

## value-of&lt;T&gt;

(Psalm 5.0+)

`value-of` ユーティリティは、任意の[array type](array_types.md) の値型を返します。

いくつかの例を示します：

- `value-of<Foo\Bar::ARRAY_CONST>``ARRAY_CONST` (Psalm 3.3+) の値型に評価されます。 `value-of<list<float>>``float` と評価される `value-of<array{a: bool, b: int}|array{c: string}>``bool|int|string` に評価される `value-of<string[]>``string` に評価される `value-of<T>`テンプレートパラメタの値型に評価される (`@template T of array` を確保)

### 列挙との併用

配列型に加えて、`value-of` を使用して、`int` または`string` を指定することもできます。 は、`BackedEnum` の可能な値の1つを含みます：

- `value-of<Suit>`は`'H'|'D'|'C'|'S'` と評価されます ([Backed enumerations](https://www.php.net/manual/en/language.enumerations.backed.php) を参照)。 `value-of<BinaryDigits>`に評価される。`0|1`

### テンプレートの使用に関する注意事項

`value-of` をテンプレート・パラメタで使用する場合、これらの許可されたメソッドでのみ型チェックを行うことができます：

-`array_values`

```php
/**
 * @template T of array
 * @param T $array
 * @return value-of<T>[]
 */
function getValues($array) {
    return array_values($array);
}
```

現在、`in_array($value, $t)` は、`$value` が次のようなものであることを ** 推測していません** 。 `value-of<T>`.

の ## properties-of&lt;T&gt;

(Psalm 5.0+)

この_utility型のコレクションは、クラスの非静的プロパティの名前をキーとし、それぞれの型を値とする、キー付き配列型を構築します。これは、オブジェクトを配列に変換する必要がある場合に便利です。

```php
class A {
  public string $foo = 'foo!';
  public int $bar = 42;

  /**
   * @return properties-of<self>
   */
  public function asArray(): array {
    return [
      'foo' => $this->foo,
      'bar' => $this->bar,
    ];
  }

  /**
   * @return list<key-of<properties-of<self>>>
   */
  public function attributeNames(): array {
    return ['foo', 'bar']
  }
}
```

### バリエーション

注意 `properties-of<T>`は **すべての非静的** プロパティを返します。特定の可視性を持つプロパティのみを選択するために、以下のサブタイプがあります：

- `public-properties-of<T>`- `protected-properties-of<T>`- `private-properties-of<T>`

### シールドアレイ対応

properties-ofやget_object_varsで密閉された配列を返したい場合は、finalクラスを使用してください：

```php
/**
 * @template T
 * @param T $object
 * @return properties-of<T>
 */
function asArray($object): array {
  return get_object_vars($object);
}


class A {
  public string $foo = 'foo!';
  public int $bar = 42;
}

final class B extends A {
  public float $baz = 2.1;
}

$a = asArray(new A);
/** @psalm-trace $a */; // array{foo: string, bar: int, ...}

$b = asArray(new B);
/** @psalm-trace $b */; // array{foo: string, bar: int, baz: float}
```

クラス文字列マップ&lt;FooのT, T&gt; ## class-string-map&lt;T of Foo, T&gt;

各値がキーに含まれるクラス文字列のインスタンスに等しい配列を示すために使われます：

```php
<?php

/**
 * @psalm-consistent-constructor
 */
class Foo {}

/**
 * @psalm-consistent-constructor
 */
class Bar extends Foo {}

class A {
  /** @var class-string-map<T of Foo, T> */
  private static array $map = [];

  /**
   * @template U of Foo
   * @param class-string<U> $class
   * @return U
   */
  public static function get(string $class) : Foo {
    if (isset(self::$map[$class])) {
      return self::$map[$class];
    }

    self::$map[$class] = new $class();
    return self::$map[$class];
  }
}

$foo = A::get(Foo::class);
$bar = A::get(Bar::class);

/** @psalm-trace $foo */; // Foo
/** @psalm-trace $bar */; // Bar
```

もし `array<class-string<Foo>, Foo>`の代わりに `class-string-map<T of Foo, T>``isset`一方、`class-string-map` を使用する場合、Psalmはキーを使用して得られた値が常に`InvalidReturnStatement` と等しいと仮定します。 `class-string<T>`は常に`T` と等しいと仮定する。  

束縛されないテンプレートは、関係のないクラスにも使うことができます：

```php
<?php

/**
 * @psalm-consistent-constructor
 */
class Foo {}

/**
 * @psalm-consistent-constructor
 */
class Bar {}

/**
 * @psalm-consistent-constructor
 */
class Baz {}

class A {
  /** @var class-string-map<T, T> */
  private static array $map = [];

  /**
   * @template U
   * @param class-string<U> $class
   * @return U
   */
  public static function get(string $class) : object {
    if (isset(self::$map[$class])) {
      return self::$map[$class];
    }

    self::$map[$class] = new $class();
    return self::$map[$class];
  }
}

$foo = A::get(Foo::class);
$bar = A::get(Bar::class);
$baz = A::get(Baz::class);

/** @psalm-trace $foo */; // Foo
/** @psalm-trace $bar */; // Bar
/** @psalm-trace $baz */; // Baz
```

##`T[K]`

指定したキーに対応する値を取得するために使用します：

```php
<?php

/**
 * @template T of array
 * @template TKey of string
 * @param T $arr
 * @param TKey $k
 * @return T[TKey]
 */
function a(array $arr, string $k): mixed {
  assert(isset($arr[$k]));
  return $arr[$k];
}

$a = a(['test' => 123], 'test');
/** @psalm-trace $a */; // 123
```

## タイプエイリアス

Psalmは、頻繁に再利用されなければならない複雑な型(配列の形など)のために、型のエイリアスを定義することができます：

```php
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

[`@psalm-import-type`](../supported_annotations.md#psalm-import-type) アノテーションを使用すると、[`@psalm-type`](../supported_annotations.md#psalm-type) で定義された型をインポートできます。

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

## 変数テンプレート

変数テンプレートでは、テンプレート・タイプの代わりに変数を直接使うことができます：

```php
<?php

/**
 * @template TA of string
 * @template TB of string
 * @template TChoose of bool
 * @param TA $a
 * @param TB $b
 * @param TChoose $choose
 * @return (TChoose is true ? TA : TB)
 */
function pick(string $a, string $b, bool $choose): string {
  return $choose ? $a : $b;
}

$a = pick('a', 'b', true);
/** @psalm-trace $a */; // 'a'

$a = pick('a', 'b', false);
/** @psalm-trace $a */; // 'b'
```

代わりに、以下のように変数テンプレートを使うことができる：

```php
<?php

/**
 * @return ($choose is true ? $a : $b)
 */
function pick(string $a, string $b, bool $choose): string {
  return $choose ? $a : $b;
}

$a = pick('a', 'b', true);
/** @psalm-trace $a */; // 'a'

$a = pick('a', 'b', false);
/** @psalm-trace $a */; // 'b'
```

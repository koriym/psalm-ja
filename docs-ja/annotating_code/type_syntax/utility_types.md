# ユーティリティ型

Psalmは、PHPの型システムに超能力をもたらすいくつかの _魔法の_ ユーティリティ型をサポートしています。

## key-of&lt;T&gt;
(Psalm 5.0+)

`key-of` ユーティリティは、任意の [配列型](array_types.md) のオフセット型を返します。

いくつかの例：
- `key-of<Foo\Bar::ARRAY_CONST>` は `ARRAY_CONST` のオフセット型を評価します（Psalm 3.3+）
- `key-of<list<mixed>>` は `int` を評価します
- `key-of<array{a: mixed, b: mixed}|array{c: mixed}>` は `'a'|'b'|'c'` を評価します
- `key-of<string[]>` は `array-key` を評価します
- `key-of<T>` はテンプレートパラメータのオフセット型を評価します（`@template T of array` を確認してください）

### テンプレート使用に関する注意

テンプレートパラメータで `key-of` を使用する場合、次の許可されたメソッドでのみ型チェックを満たすことができます：
- `array_keys($t)`
- `array_key_first($t)`
- `array_key_last($t)`

現在、`array_key_exists($key, $t)` は `$key` が `key-of<T>` であると推論 **しません**。

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

`value-of` ユーティリティは、任意の [配列型](array_types.md) の値型を返します。

いくつかの例：
- `value-of<Foo\Bar::ARRAY_CONST>` は `ARRAY_CONST` の値型を評価します（Psalm 3.3+）
- `value-of<list<float>>` は `float` を評価します
- `value-of<array{a: bool, b: int}|array{c: string}>` は `bool|int|string` を評価します
- `value-of<string[]>` は `string` を評価します
- `value-of<T>` はテンプレートパラメータの値型を評価します（`@template T of array` を確認してください）

### 列挙型での使用

配列型に加えて、`value-of` は `BackedEnum` の可能な値の1つを含む `int` または `string` を指定するためにも使用できます：
- `value-of<Suit>` は `'H'|'D'|'C'|'S'` を評価します（[バックド列挙型](https://www.php.net/manual/en/language.enumerations.backed.php) を参照）
- `value-of<BinaryDigits>` は `0|1` を評価します

### テンプレート使用に関する注意

テンプレートパラメータで `value-of` を使用する場合、次の許可されたメソッドでのみ型チェックを満たすことができます：
- `array_values`

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

現在、`in_array($value, $t)` は `$value` が `value-of<T>` であると推論 **しません**。

## properties-of&lt;T&gt;
(Psalm 5.0+)

この _ユーティリティ型_ のコレクションは、クラスの非静的プロパティの名前をキーとし、それぞれの型を値とするキー付き配列型を構築します。これは、オブジェクトを配列に変換する必要がある場合に便利です。

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

`properties-of<T>` は **すべての非静的** プロパティを返すことに注意してください。特定の可視性を持つプロパティのみを選択するために、以下のサブタイプがあります：
- `public-properties-of<T>`
- `protected-properties-of<T>`
- `private-properties-of<T>`

### シールド配列のサポート

properties-of と get_object_vars がシールド配列を返すようにしたい場合は、finalクラスを使用してください：

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
/*

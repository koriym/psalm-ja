# アサーション構文

Psalmの[アサーションアノテーション](adding_assertions.md)は、さまざまなアサーションをサポートしています。

Psalmのアサーションは以下の形式を取ります：
`@psalm-assert(-if-true|-if-false)? (Assertion) (Variable or Property)`

ここでの`Assertion`には多くの形式があります：

## 通常のアサーション

### is_xxx アサーション

ほとんどの`is_xxx` PHP関数には、例えば`is_int`に対する`int`のような対応するアサーションがあります。以下は完全なリストです：

- `int`
- `float`
- `string`
- `bool`
- `scalar`
- `callable`
- `countable`
- `array`
- `iterable`
- `numeric`
- `resource`
- `object`
- `null`

したがって、カスタムバージョンの`is_int`は、Psalmで以下のようにアノテーションを付けることができます：

```php
<?php
/** @psalm-assert-if-true int $x */
function custom_is_int($x) {
  return is_int($x);
}
```

### オブジェクト型アサーション

任意のクラスをアサーションとして使用できます。例：
`@psalm-assert SomeObjectType $foo`

### ジェネリックアサーション

ジェネリック型パラメータもアサートできるようになりました。例：
`@psalm-assert array<int, string> $foo`

## 否定アサーション

上記のどのアサーションも否定することができます：

これは`$foo`が`int`ではないことをアサートします：

```php
<?php
/** @psalm-assert !int $foo */
```

これは`$bar`が`SomeObjectType`型のオブジェクトではないことをアサートします：

```php
<?php
/** @psalm-assert !SomeObjectType $bar  */
```

## ブールアサーション

これは`$bar`が`true`であることをアサートします：

```php
<?php
/** @psalm-assert true $bar  */
```

これは`$bar`が`false`ではないことをアサートします：

```php
<?php
/** @psalm-assert !false $bar  */
```

## 等価性アサーション

Psalmは`assert($some_int === $other_int)`と同等のものを以下の形式でサポートしています：

```php
<?php
/** @psalm-assert =int $some_int */
```

以下のアサーションとの間には2つの違いがあります：

```php
<?php
/** @psalm-assert int $some_int */
```

まず、`=int`の否定には意味がありません：

```php
<?php
/** @psalm-assert-if-true =int $x */
function equalsFive($x) {
  return is_int($x) && $x === 5;
}

function foo($y) : void {
  if (equalsFive($y)) {
    // $yは確実にintです
  } else {
    // $yはintかもしれませんが、そうでないかもしれません
  }
}

function bar($y) : void {
  if (is_int($y)) {
    // $yは確実にintです
  } else {
    // $yは確実にintではありません
  }
}
```

次に、`equalsFive($some_int)`を呼び出すことは、Psalmでは`RedundantCondition`ではありませんが、`is_int($some_int)`を呼び出すことは`RedundantCondition`です。

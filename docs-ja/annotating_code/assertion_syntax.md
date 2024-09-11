# アサーション構文

Psalm の[assertion annotation](adding_assertions.md) は、さまざまなアサーションをサポートしている。

Psalm のアサーションは次のような形式です。

`@psalm-assert(-if-true|-if-false)? (Assertion) (Variable or Property)`

`Assertion` である：

## 通常のアサーション

## is_xxx アサーション

ほとんどの`is_xxx` PHP 関数には、それに付随するアサーションがあります。 たとえば`int` は`is_int` のアサーションとなります：

-`int` -`float` -`string` -`bool` -`scalar` -`callable` -`countable` -`array` -`iterable` -`numeric` -`resource` -`object` - です。`null`

つまり、カスタムバージョン`is_int` は、Psalmでは次のようにアノテーションされます。

```php
<?php
/** @psalm-assert-if-true int $x */
function custom_is_int($x) {
  return is_int($x);
}
```

### オブジェクト型のアサーション

どのクラスもアサーションとして使うことができます。

`@psalm-assert SomeObjectType $foo`

### 汎用アサーション

汎用型パラメータもアサーションできるようになりました。

`@psalm-assert array<int, string> $foo`

## 否定されたアサーション

上記のどのアサーションも否定することができる：

これは、`$foo` は`int` ではないことを表しています：

```php
<?php
/** @psalm-assert !int $foo */
```

これは、`$bar` が`SomeObjectType` 型のオブジェクトではないことを表明している：
```php
<?php
/** @psalm-assert !SomeObjectType $bar  */
```

## ブールのアサーション

これは`$bar` が`true` であることを表明する：
```php
<?php
/** @psalm-assert true $bar  */
```

これは、`$bar` が`false` でないことを表明する：
```php
<?php
/** @psalm-assert !false $bar  */
```

## 等式アサーション

Psalmは、`assert($some_int === $other_int)` に相当する以下の形式もサポートしている。
```php
<?php
/** @psalm-assert =int $some_int */
```

上記のアサーションと 

```php
<?php
/** @psalm-assert int $some_int */
```

第一に、`=int` の否定には意味がない：

```php
<?php
/** @psalm-assert-if-true =int $x */
function equalsFive($x) {
  return is_int($x) && $x === 5;
}

function foo($y) : void {
  if (equalsFive($y)) {
    // $y is definitely an int
  } else {
    // $y might be an int, but it might not
  }
}

function bar($y) : void {
  if (is_int($y)) {
    // $y is definitely an int
  } else {
    // $y is definitely not an int
  }
}
```

第二に、`equalsFive($some_int)` の呼び出しは詩篇の`RedundantCondition` ではないが、`is_int($some_int)` の呼び出しはそうである。



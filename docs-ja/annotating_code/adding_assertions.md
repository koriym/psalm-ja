# アサーションの追加

Psalmには5つのdocblockアノテーションがあり、関数が変数やプロパティに関する事実を検証することを指定できます：

-`@psalm-assert` (例外をスローするときに使う) -`@psalm-assert-if-true`/`@psalm-assert-if-false` (`bool` を返すときに使う) -`@psalm-if-this-is`/`@psalm-this-out` (メソッドを呼び出すときに使う)

[can be found here](assertion_syntax.md) 。

## 例

入力が文字列の配列であることを検証するクラスがある場合、Psalmにそれを明示することができます：

```php
<?php
/** @psalm-assert string[] $arr */
function validateStringArray(array $arr) : void {
    foreach ($arr as $s) {
        if (!is_string($s)) {
          throw new UnexpectedValueException('Invalid value ' . gettype($s));
        }
    }
}
```

これにより、あるデータに対して`validateStringArray` 関数を呼び出し、Psalm に、与えられたデータは文字列の配列でなければならないと理解させることができます：

```php
<?php
function takesString(string $s) : void {}
function takesInt(int $s) : void {}

function takesArray(array $arr) : void {
    takesInt($arr[0]); // this is fine

    validateStringArray($arr);

    takesInt($arr[0]); // this is an error

    foreach ($arr as $a) {
        takesString($a); // this is fine
    }
}
```

同様に、`@psalm-assert-if-true` と`@psalm-assert-if-false` は、関数/メソッドがそれぞれ`true` と`false` を返す場合、入力をフィルタリングします：

```php
<?php
class A {
    public function isValid() : bool {
        return (bool) rand(0, 1);
    }
}
class B extends A {
    public function bar() : void {}
}

/**
 * @psalm-assert-if-true B $a
 */
function isValidB(A $a) : bool {
    return $a instanceof B && $a->isValid();
}

/**
 * @psalm-assert-if-false B $a
 */
function isInvalidB(A $a) : bool {
    return !$a instanceof B || !$a->isValid();
}

function takesA(A $a) : void {
    if (isValidB($a)) {
        $a->bar();
    }

    if (isInvalidB($a)) {
        // do something
    } else {
        $a->bar();
    }

    $a->bar(); //error
}
```

Psalmに与えられたデータが特定の型でなければならないことを理解させるだけでなく、変数がnullであってはならないことを示すこともできる：

```php
<?php
/**
 * @psalm-assert !null $value
 */
function assertNotNull($value): void {
  // Some check that will mean the method will only complete if $value is not null.
}
```

また、NULL値をチェックすることもできる：

```php
<?php
/**
 * @psalm-assert-if-true null $value
 */
function isNull($value): bool {
  return ($value === null);
}
```

### メソッドの戻り値のアサーション

`@psalm-assert-if-true` および`@psalm-assert-if-false` アノテーションを使用して、クラス内のメソッドの戻り値をアサートすることもできます。ご覧のように、Psalm では同じ DocBlock で複数のアノテーションを指定することもできます：

```php
<?php
class Result {
    /**
     * @var ?Exception
     */
    private $exception;

    /**
     * @psalm-assert-if-true Exception $this->exception
     * @psalm-assert-if-true Exception $this->getException()
     */
    public function hasException(): bool {
        return $this->exception !== null;
    }

    public function getException(): ?Exception {
        return $this->exception;
    }

    public function foo(): void {
        if( $this->hasException() ) {
            // Psalm now knows that $this->exception is an instance of Exception
            echo $this->exception->getMessage();
        }
    }
}

$result = new Result;

if( $result->hasException() ) {
    // Psalm now knows that $result->getException() will return an instance of Exception
    echo $result->getException()->getMessage();
}
```

上の例は、設定ファイルで[method call memoization](https://psalm.dev/docs/running_psalm/configuration/#memoizemethodcallresults) を有効にするか、クラスを[immutable](https://psalm.dev/docs/annotating_code/supported_annotations/#psalm-immutable) とアノテーションした場合のみ動作することに注意してください。


`@psalm-this-out` を使用して、メソッド呼び出し後にメソッドのテンプレート引数を変更し、オブジェクトの内部状態の変更を反映させることができます。   また、`@psalm-if-this-is` を使って、オブジェクトのテンプレート引数に対してアサーションを行うこともできます。  


```php
<?php

/**
 * @template T
 */
class a {
    /**
     * @var list<T>
     */
    private array $data;
    /**
     * @param T $data
     */
    public function __construct($data) {
        $this->data = [$data];
    }
    /**
     * @template NewT
     * 
     * @param NewT $data
     * 
     * @psalm-this-out self<T|NewT>
     * 
     * @return void
     */
    public function addData($data) {
        /** @var self<T|NewT> $this */
        $this->data []= $data;
    }
    /**
     * @template NewT
     * 
     * @param NewT $data
     * 
     * @psalm-this-out self<NewT>
     * 
     * @return void
     */
    public function setData($data) {
        /** @var self<NewT> $this */
        $this->data = [$data];
    }
    /**
     * @psalm-if-this-is a<int>
     */
    public function test(): void {
    }
}

$i = new a(123);
// OK - $i is a<123>
$i->test();

$i->addData(321);
// OK - $i is a<123|321>
$i->test();

$i->setData("test");
// IfThisIsMismatch - Class is not a<int> as required by psalm-if-this-is
$i->test();
```

# アサーションの追加

Psalmには、変数やプロパティに関する事実を関数が検証することを指定できる5つのdocblockアノテーションがあります：

- `@psalm-assert`（例外をスローする場合に使用）
- `@psalm-assert-if-true`/`@psalm-assert-if-false`（`bool`を返す場合に使用）
- `@psalm-if-this-is`/`@psalm-this-out`（メソッドを呼び出す場合に使用）

許容されるアサーションの一覧は[こちらで見つかります](assertion_syntax.md)。

## 例

入力が文字列の配列であることを検証するクラスがある場合、それをPsalmに明確に伝えることができます：

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

これにより、`validateStringArray`関数を一部のデータに対して呼び出し、与えられたデータが文字列の配列でなければならないことをPsalmに理解させることができます：

```php
<?php
function takesString(string $s) : void {}
function takesInt(int $s) : void {}

function takesArray(array $arr) : void {
    takesInt($arr[0]); // これは問題ありません
    validateStringArray($arr);
    takesInt($arr[0]); // これはエラーです
    foreach ($arr as $a) {
        takesString($a); // これは問題ありません
    }
}
```

同様に、`@psalm-assert-if-true`と`@psalm-assert-if-false`は、関数/メソッドがそれぞれ`true`と`false`を返す場合に入力をフィルタリングします：

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
        // 何かを行う
    } else {
        $a->bar();
    }
    
    $a->bar(); // エラー
}
```

与えられたデータが特定の型でなければならないことをPsalmに理解させるだけでなく、変数がnullでないことも示すことができます：

```php
<?php
/** 
 * @psalm-assert !null $value
 */
function assertNotNull($value): void {
  // $valueがnullでない場合にのみメソッドが完了するようなチェック
}
```

そして、null値をチェックすることもできます：

```php
<?php
/** 
 * @psalm-assert-if-true null $value
 */
function isNull($value): bool {
  return ($value === null);
}
```

### メソッドの戻り値のアサート

`@psalm-assert-if-true`と`@psalm-assert-if-false`アノテーションを使用して、クラス内のメソッドの戻り値をアサートすることもできます。ご覧のように、Psalmは同じDocBlock内で複数のアノテーションを指定することも許可しています：

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
            // Psalmは$this->exceptionがExceptionのインスタンスであることを知っています
            echo $this->exception->getMessage();
        }
    }
}

$result = new Result;
if( $result->hasException() ) {
    // Psalmは$result->getException()がExceptionのインスタンスを返すことを知っています
    echo $result->getException()->getMessage();
}
```

注意：上の例は、設定ファイルで[メソッド呼び出しのメモ化](https://psalm.dev/docs/running_psalm/configuration/#memoizemethodcallresults)を有効にするか、クラスを[不変](https://psalm.dev/docs/annotating_code/supported_annotations/#psalm-immutable)としてアノテーションを付ける場合にのみ機能します。

`@psalm-this-out`を使用して、メソッド呼び出し後のメソッドのテンプレート引数を変更し、オブジェクトの内部状態の変更を反映させることができます。また、`@psalm-if-this-is`を使用して、オブジェクトのテンプレート引数に関するアサーションを行うこともできます。

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
// OK - $i は a<123> です
$i->test();

$i->addData(321);
// OK - $i は a<123|321> です
$i->test();

$i->setData("test");
// IfThisIsMismatch - クラスは psalm-if-this-is で要求される a<int> ではありません
$i->test();
```

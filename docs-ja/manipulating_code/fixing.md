# コードの修正

Psalmは大規模なコードベースから潜在的な問題を発見するのは得意だが、いったん発見してしまうと、すべての問題を修正するのは途方もない作業になる。

PsalmにはPsalterというコードが修正できるツールが付属している。

バイナリの

```
vendor/bin/psalter [args]
```

またはPsalmのバイナリ経由で実行できる：

```
vendor/bin/psalm --alter [args]
```

## 安全機能

コードの更新は本質的にリスクが高く、自動更新はさらにリスクが高い。少しでも安心できるように、いくつかの機能を追加しました：

- Psalterがどのような変更を行うのかを事前に確認するには、`--dry-run` を使って実行することができます。 -`--php-version` を使って、特定のバージョンのPHPをターゲットにすることができます。 これにより、(例えば) PHP 7.0のコードにNULL可能なタイプヒントを追加しないようにしたり、 PHP 5.6のコードに全くタイプヒントを追加しないようにしたりすることができます。`--php-version` のデフォルトは現在のバージョンです。-`--safe-types` モードがあり、PHP 7 の return typehint のみを Psalm がドックブロック以外の型情報源から収集した情報 (typehinted params や`instanceof` チェック、他の return typehint など) で更新します。`--allow-backwards-incompatible-changes=false` を使用すると、後方互換性のない変更を作成しないようにすることができます。


## プラグイン

独自の操作プラグインを渡すことができます。
```bash
vendor/bin/psalter --plugin=vendor/vimeo/psalm/examples/plugins/ClassUnqualifier.php --dry-run
```

上の例のプラグインは、コード中の不必要に修飾されたクラス名を、 より短いエイリアスに変換します。

## サポートされる修正

この初期リリースでは、Psalmが発見した問題の名前に対応する以下の変更をサポートしています。これらすべてを一度に修正するには`vendor/bin/psalter --issues=all`

### を実行する。

で`vendor/bin/psalter --issues=MissingReturnType --php-version=7.0` を実行する。

```php
<?php
function foo() {
  return "hello";
}
```

与える

```php
<?php
function foo() : string {
  return "hello";
}
```

で`vendor/bin/psalter --issues=MissingReturnType --php-version=5.6` 。

```php
<?php
function foo() {
  return "hello";
}
```

与える

```php
<?php
/**
 * @return string
 */
function foo() {
  return "hello";
}
```

### となる。

上記と同様。

### 無効な返り値

`vendor/bin/psalter --issues=InvalidReturnType` 。

```php
<?php
/**
 * @return int
 */
function foo() {
  return "hello";
}
```

与える

```php
<?php
/**
 * @return string
 */
function foo() {
  return "hello";
}
```

return型ヒントのサポートもあるので、`vendor/bin/psalter --issues=InvalidReturnType` 。

```php
<?php
function foo() : int {
  return "hello";
}
```

を実行すると

```php
<?php
function foo() : string {
  return "hello";
}
```

### を返します。

`vendor/bin/psalter --issues=InvalidNullableReturnType  --php-version=7.1` 。

```php
<?php
function foo() : string {
  return rand(0, 1) ? "hello" : null;
}
```

与える

```php
<?php
function foo() : ?string {
  return rand(0, 1) ? "hello" : null;
}
```

で`vendor/bin/psalter --issues=InvalidNullableReturnType  --php-version=7.0` 。

```php
<?php
function foo() : string {
  return rand(0, 1) ? "hello" : null;
}
```

与える

```php
<?php
/**
 * @return string|null
 */
function foo() {
  return rand(0, 1) ? "hello" : null;
}
```

### を返します。

`vendor/bin/psalter --issues=InvalidFalsableReturnType` 。

```php
<?php
function foo() : string {
  return rand(0, 1) ? "hello" : false;
}
```

与える

```php
<?php
/**
 * @return string|false
 */
function foo() {
  return rand(0, 1) ? "hello" : false;
}
```

### MissingParamType

`vendor/bin/psalter --issues=MissingParamType` 。

```php
<?php
class C {
  public static function foo($s) : void {
    echo $s;
  }
}
C::foo("hello");
```

与える

```php
<?php
class C {
  /**
   * @param string $s
   */
  public static function foo($s) : void {
    echo $s;
  }
}
C::foo("hello");
```

### MissingPropertyType

`vendor/bin/psalter --issues=MissingPropertyType` 。

```php
<?php
class A {
    public $foo;
    public $bar;
    public $baz;

    public function __construct()
    {
        if (rand(0, 1)) {
            $this->foo = 5;
        } else {
            $this->foo = "hello";
        }

        $this->bar = "baz";
    }

    public function setBaz() {
        $this->baz = [1, 2, 3];
    }
}
```

与える

```php
<?php
class A {
    /**
     * @var string|int
     */
    public $foo;

    public string $bar;

    /**
     * @var array<int, int>|null
     * @psalm-var non-empty-list<int>|null
     */
    public $baz;

    public function __construct()
    {
        if (rand(0, 1)) {
            $this->foo = 5;
        } else {
            $this->foo = "hello";
        }

        $this->bar = "baz";
    }

    public function setBaz() {
        $this->baz = [1, 2, 3];
    }
}
```

### MismatchingDocblockParamType

与えられた

```php
<?php
class A {}
class B extends A {}
class C extends A {}
class D {}
```

で`vendor/bin/psalter --issues=MismatchingDocblockParamType` 。
```php
<?php
/**
 * @param B|C $first
 * @param D $second
 */
function foo(A $first, A $second) : void {}
```

与える

```php
<?php
/**
 * @param B|C $first
 * @param A $second
 */
function foo(A $first, A $second) : void {}
```

### MismatchingDocblockReturnType

`vendor/bin/psalter --issues=MismatchingDocblockReturnType` 。
```php
<?php
/**
 * @return int
 */
function foo() : string {
  return "hello";
}
```

与える

```php
<?php
/**
 * @return string
 */
function foo() : string {
  return "hello";
}
```

### LessSpecificReturnType

`vendor/bin/psalter --issues=LessSpecificReturnType` 。

```php
<?php
function foo() : ?string {
  return "hello";
}
```

与える

```php
<?php
function foo() : string {
  return "hello";
}
```

### 未定義変数の可能性

で`vendor/bin/psalter --issues=PossiblyUndefinedVariable` を実行する。

```php
<?php
function foo()
{
    if (rand(0, 1)) {
      $a = 5;
    }
    echo $a;
}
```

与える

```php
<?php
function foo()
{
    $a = null;
    if (rand(0, 1)) {
      $a = 5;
    }
    echo $a;
}
```


### となる。

で`vendor/bin/psalter --issues=PossiblyUndefinedGlobalVariable` を実行する。

```php
<?php
if (rand(0, 1)) {
  $a = 5;
}
echo $a;
```

与える

```php
<?php
$a = null;
if (rand(0, 1)) {
  $a = 5;
}
echo $a;
```

### 未使用メソッド

未使用のプライベート・メソッドを削除します。

`vendor/bin/psalter --issues=UnusedMethod` を

```php
<?php
class A {
    private function foo() : void {}
}

new A();
```

与える

```php
<?php
class A {

}

new A();
```

### 使用されない可能性のあるメソッド

protected/public の未使用メソッドを削除します。

`vendor/bin/psalter --issues=PossiblyUnusedMethod` を

```php
<?php
class A {
    protected function foo() : void {}
    public function bar() : void {}
}

new A();
```

与える

```php
<?php
class A {

}

new A();
```

### 未使用プロパティ

プライベートな未使用プロパティを削除します。

で`vendor/bin/psalter --issues=UnusedProperty` を実行します。

```php
<?php
class A {
    /** @var string */
    private $foo;
}

new A();
```

与える

```php
<?php
class A {

}

new A();
```

### 未使用の可能性があるプロパティ

protected/public の未使用プロパティを削除します。

で`vendor/bin/psalter --issues=PossiblyUnusedProperty` を実行します。

```php
<?php
class A {
    /** @var string */
    public $foo;

    /** @var string */
    protected $bar;
}

new A();
```

与える

```php
<?php
class A {

}

new A();
```

### 未使用変数

未使用の変数を削除します。

`vendor/bin/psalter --issues=UnusedVariable` を

```php
<?php
function foo(string $s) : void {
    $a = 5;
    $b = 6;
    $c = $b += $a -= intval($s);
    echo "foo";
}
```

与える

```php
<?php
function foo(string $s) : void {
    echo "foo";
}
```

### UnnecessaryVarAnnotation

これは、未使用の`@var` アノテーションを削除します。

`vendor/bin/psalter --issues=UnnecessaryVarAnnotation` 。

```php
<?php
function foo() : string {
    return "hello";
}

/** @var string */
$a = foo();
```

与える

```php
<?php
function foo() : string {
    return "hello";
}

$a = foo();
```

### ParamNameMismatch

これは、子クラスのパラメータ名をその親クラスに揃えます。

で`vendor/bin/psalter --issues=ParamNameMismatch` を実行します。

```php
<?php

class A {
    public function foo(string $str, bool $b = false) : void {}
}

class AChild extends A {
    public function foo(string $string, bool $b = false) : void {
        echo $string;
    }
}
```

与える

```php
<?php

class A {
    public function foo(string $str, bool $b = false) : void {}
}

class AChild extends A {
    public function foo(string $str, bool $b = false) : void {
        echo $str;
    }
}
```

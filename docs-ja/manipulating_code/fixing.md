# コードの修正

Psalmは大規模なコードベースの潜在的な問題を見つけるのが得意ですが、一度見つかると、すべての問題を修正するのは途方もない作業になることがあります。

Psalmには、Psalterという名前のツールが付属しており、コードの修正に役立ちます。

以下のように、バイナリを通じて実行できます：

```
vendor/bin/psalter [args]
```

または、Psalmのバイナリを通じて：

```
vendor/bin/psalm --alter [args]
```

## 安全機能

コードの更新には本質的にリスクがあり、自動的に行うとさらにリスクが高くなります。少し安心できるように、いくつかの機能を追加しました：

- Psalterが行う変更を事前に確認するには、`--dry-run`オプションを付けて実行できます。
- `--php-version`を使用して特定のPHPバージョンを対象にできるので、例えばPHP 7.0のコードにnullable型ヒントを追加したり、PHP 5.6のコードに型ヒントを全く追加しないようにできます。`--php-version`はデフォルトで現在のバージョンになります。
- `--safe-types`モードがあり、これはPsalmがdocblock以外のソース（例：型ヒント付きパラメータ、`instanceof`チェック、他の戻り値の型ヒントなど）から収集した情報のみを使用してPHP 7の戻り値の型ヒントを更新します。
- `--allow-backwards-incompatible-changes=false`を使用すると、後方互換性のない変更が作成されないようにできます。

## プラグイン

独自の操作プラグインを渡すことができます。例：

```bash
vendor/bin/psalter --plugin=vendor/vimeo/psalm/examples/plugins/ClassUnqualifier.php --dry-run
```

上記の例のプラグインは、コード内の不必要に修飾されたクラス名をすべて、より短い別名バージョンに変換します。

## サポートされている修正

この初期リリースでは、Psalmが見つける問題の名前に対応する以下の変更をサポートしています。

これらすべてを一度に修正するには、`vendor/bin/psalter --issues=all`を実行します。

### MissingReturnType

`vendor/bin/psalter --issues=MissingReturnType --php-version=7.0`を以下のコードで実行すると：

```php
<?php
function foo() {
  return "hello";
}
```

結果：

```php
<?php
function foo() : string {
  return "hello";
}
```

`vendor/bin/psalter --issues=MissingReturnType --php-version=5.6`を以下のコードで実行すると：

```php
<?php
function foo() {
  return "hello";
}
```

結果：

```php
<?php
/** 
 * @return string 
 */
function foo() {
  return "hello";
}
```

### MissingClosureReturnType

上記と同様ですが、クロージャに対して適用されます。

### InvalidReturnType

`vendor/bin/psalter --issues=InvalidReturnType`を以下のコードで実行すると：

```php
<?php
/** 
 * @return int 
 */
function foo() {
  return "hello";
}
```

結果：

```php
<?php
/** 
 * @return string 
 */
function foo() {
  return "hello";
}
```

戻り値の型ヒントもサポートされているので、`vendor/bin/psalter --issues=InvalidReturnType`を以下のコードで実行すると：

```php
<?php
function foo() : int {
  return "hello";
}
```

結果：

```php
<?php
function foo() : string {
  return "hello";
}
```

### InvalidNullableReturnType

`vendor/bin/psalter --issues=InvalidNullableReturnType  --php-version=7.1`を以下のコードで実行すると：

```php
<?php
function foo() : string {
  return rand(0, 1) ? "hello" : null;
}
```

結果：

```php
<?php
function foo() : ?string {
  return rand(0, 1) ? "hello" : null;
}
```

`vendor/bin/psalter --issues=InvalidNullableReturnType  --php-version=7.0`を以下のコードで実行すると：

```php
<?php
function foo() : string {
  return rand(0, 1) ? "hello" : null;
}
```

結果：

```php
<?php
/** 
 * @return string|null 
 */
function foo() {
  return rand(0, 1) ? "hello" : null;
}
```

### InvalidFalsableReturnType

`vendor/bin/psalter --issues=InvalidFalsableReturnType`を以下のコードで実行すると：

```php
<?php
function foo() : string {
  return rand(0, 1) ? "hello" : false;
}
```

結果：

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

`vendor/bin/psalter --issues=MissingParamType`を以下のコードで実行すると：

```php
<?php
class C {
  public static function foo($s) : void {
    echo $s;
  }
}
C::foo("hello");
```

結果：

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

`vendor/bin/psalter --issues=MissingPropertyType`を以下のコードで実行すると：

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

結果：

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

以下のコードがある場合：

```php
<?php
class A {}
class B extends A {}
class C extends A {}
class D {}
```

`vendor/bin/psalter --issues=MismatchingDocblockParamType`を以下のコードで実行すると：

```php
<?php
/** 
 * @param B|C $first
 * @param D $second 
 */
function foo(A $first, A $second) : void {}
```

結果：

```php
<?php
/** 
 * @param B|C $first
 * @param A $second 
 */
function foo(A $first, A $second) : void {}
```

### MismatchingDocblockReturnType

`vendor/bin/psalter --issues=MismatchingDocblockReturnType`を以下のコードで実行すると：

```php
<?php
/** 
 * @return int 
 */
function foo() : string {
  return "hello";
}
```

結果：

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

`vendor/bin/psalter --issues=LessSpecificReturnType`を以下のコードで実行すると：

```php
<?php
function foo() : ?string {
  return "hello";
}
```

結果：

```php
<?php
function foo() : string {
  return "hello";
}
```

### PossiblyUndefinedVariable

`vendor/bin/psalter --issues=PossiblyUndefinedVariable`を以下のコードで実行すると：

```php
<?php
function foo(){
    if (rand(0, 1)) {
      $a = 5;
    }
    echo $a;
}
```

結果：

```php
<?php
function foo(){
    $a = null;
    if (rand(0, 1)) {
      $a = 5;
    }
    echo $a;
}
```

### PossiblyUndefinedGlobalVariable

`vendor/bin/psalter --issues=PossiblyUndefinedGlobalVariable`を以下のコードで実行すると：

```php
<?php
if (rand(0, 1)) {
  $a = 5;
}
echo $a;
```

結果：

```php
<?php
$a = null;
if (rand(0, 1)) {
  $a = 5;
}
echo $a;
```

### UnusedMethod

これは使用されていないプライベートメソッドを削除します。

`vendor/bin/psalter --issues=UnusedMethod`を以下のコードで実行すると：

```php
<?php
class A {
    private function foo() : void {}
}
new A();
```

結果：

```php
<?php
class A {}
new A();
```

### PossiblyUnusedMethod

これは使用されていないprotected/publicメソッドを削除します。

`vendor/bin/psalter --issues=PossiblyUnusedMethod`を以下のコードで実行すると：

```php
<?php
class A {
    protected function foo() : void {}
    public function bar() : void {}
}
new A();
```

結果：

```php
<?php
class A {}
new A();
```

### UnusedProperty

これは使用されていないプライベートプロパティを削除します。

`vendor/bin/psalter --issues=UnusedProperty`を以下のコードで実行すると：

```php
<?php
class A {
    /** @var string */
    private $foo;
}
new A();
```

結果：

```php
<?php
class A {}
new A();
```

### PossiblyUnusedProperty

これは使用されていないprotected/publicプロパティを削除します。

`vendor/bin/psalter --issues=PossiblyUnusedProperty`を以下のコードで実行すると：

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

結果：

```php
<?php
class A {}
new A();
```

### UnusedVariable

これは使用されていない変数を削除します。

`vendor/bin/psalter --issues=UnusedVariable`を以下のコードで実行すると：

```php
<?php
function foo(string $s) : void {
    $a = 5;
    $b = 6;
    $c = $b += $a -= intval($s);
    echo "foo";
}
```

結果：

```php
<?php
function foo(string $s) : void {
    echo "foo";
}
```

### UnnecessaryVarAnnotation

これは使用されていない`@var`アノテーションを削除します。

`vendor/bin/psalter --issues=UnnecessaryVarAnnotation`を以下のコードで実行すると：

```php
<?php
function foo() : string {
    return "hello";
}
/** @var string */
$a = foo();
```

結果：

```php
<?php
function foo() : string {
    return "hello";
}
$a = foo();
```

### ParamNameMismatch

これは子クラスのパラメータ名を親クラスと一致させます。

`vendor/bin/psalter --issues=ParamNameMismatch`を以下のコードで実行すると：

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

結果：

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

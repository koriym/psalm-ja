# 偽陽性を避ける

初めてPsalmのテイント分析を実行したとき、偽陽性がたくさん表示されるかもしれません。

偽陽性を好む人はいません！

誤検出を防ぐ方法はいくつかあります：

## 汚染された入力から逃れる

例えば、`$_GET['name']` を`htmlentities` でラップすることで、`$_GET` のクロスサイトスクリプティング攻撃を防ぐことができます。

Psalmでは `@psalm-taint-escape <taint-type>`というアノテーションを使うことができます：

```php
<?php

function echoVar(string $str) : void {
    /**
     * @psalm-taint-escape html
     */
    $str = str_replace(['<', '>'], '', $str);
    echo $str;
}

echoVar($_GET["text"]);
```

## 汚染された入力を条件付きでエスケープする

先ほどの例を少し修正したものが、戻り値が安全であるとみなされるかどうかを決定するために条件を使用しています。関数の引数`$escape` が真の場合のみ、対応するアノテーション
`@psalm-taint-escape` `html` が適用されます。

```php
<?php /**  * @param string $str  * @param bool $escape  * @psalm-taint-escape ($escape is true ? 'html' : null)  */ function processVar(string $str, bool $escape = true) : string {     if ($escape) {       $str = str_replace(['<', '>'], '', $str);     }     return $str; }

echo processVar($_GET['text'], false); // detects tainted HTML echo processVar($_GET['text'], true); // considered secure
```

## HTMLユーザー入力のサニタイズ

可能な限り、アプリケーションはHTMLのブロックではなく、個別のテキストフィールドと してユーザー入力を受け付け、保存するように設計されるべきです。  これにより、`htmlspecialchars` や`htmlentities` を使って、ユーザー入力を完全にエスケープすることができます。 HTMLのユーザー入力が必要な場合(例えば、[TinyMCE](https://www.tiny.cloud/) のようなリッチテキストエディタ)には、危険なHTMLをフィルタリングするために特別に設計されたライブラリを使うことを強く推奨します。  例えば、[HTML Purifier](http://htmlpurifier.org/docs) は次のように使うことができる：

```php
<?php

/**  * @psalm-taint-escape html  * @psalm-taint-escape has_quotes  */ function sanitizeHTML($html){     $purifier = new HTMLPurifier();     return $purifier->purify($html); }
```

## 関数内のテイントに特化する

関数、メソッド、クラスでは、`@psalm-taint-specialize` アノテーションを使うことができます。

```php
<?php

function takesInput(string $s) : string {     return $s; }

echo htmlentities(takesInput($_GET["name"])); echo takesInput("hello"); // Psalm detects tainted HTML here
```

`@psalm-taint-specialize` アノテーションを追加することで、関数の各呼び出しが別々に扱われるべきであることをPsalmに伝えることで、問題を解決します。

```php
<?php

/**  * @psalm-taint-specialize  */ function takesInput(string $s) : string {     return $s; }

echo htmlentities(takesInput($_GET["name"])); echo takesInput("hello"); // No error
```

特殊化された関数やメソッドは、汚染された入力を追跡します：

```php
<?php

/**  * @psalm-taint-specialize  */ function takesInput(string $s) : string {     return $s; }

echo takesInput($_GET["name"]); // Psalm detects tainted input echo takesInput("hello"); // No error
```

ここで私たちは、関数の汚染度は関数への入力に完全に依存していることをプサルムに伝えている。

もしあなたが[immutability in Psalm](https://psalm.dev/articles/immutability-and-beyond) に詳しいなら、この一般的な考え方はよく理解できるはずです。なぜなら純粋関数とは、出力が完全に入力に依存する関数のことだからです。当然のことながら、`@psalm-pure` の関数はすべて、入力に基づいて出力の汚染度を特化します：

```php
<?php

/**  * @psalm-pure  */ function takesInput(string $s) : string {     return $s; }

echo htmlentities(takesInput($_GET["name"])); echo takesInput("hello"); // No error
```

## クラスにおける汚染度の特化

テイントを関数呼び出しに特化させることができるように、テイント・プロパティも指定したクラスに特化させることができます。

```php
<?php

class User {     public string $name;

    public function __construct(string $name) {         $this->name = $name;     } }

/**  * @psalm-taint-specialize  */ function echoUserName(User $user) {     echo $user->name; // Error, detected tainted input }

$user1 = new User("Keith"); $user2 = new User($_GET["name"]);

echoUserName($user1);
```

クラスに`@psalm-taint-specialize` 。

```php
<?php

/**  * @psalm-taint-specialize  */ class User {     public string $name;

    public function __construct(string $name) {         $this->name = $name;     } }

/**  * @psalm-taint-specialize  */ function echoUserName(User $user) {     echo $user->name; // No error }

$user1 = new User("Keith"); $user2 = new User($_GET["name"]);

echoUserName($user1);
```

また、純潔を強制する形なので、`@psalm-immutable` ：

```php
<?php

/**  * @psalm-immutable  */ class User {     public string $name;

    public function __construct(string $name) {         $this->name = $name;     } }

/**  * @psalm-taint-specialize  */ function echoUserName(User $user) {     echo $user->name; // No error }

$user1 = new User("Keith"); $user2 = new User($_GET["name"]);

echoUserName($user1);
```

## テイントパスのファイルを避ける

Psalmの設定に特定のファイルやディレクトリを経由するテイントパスを指定することで、Psalmにテイントパスに興味がないことを伝えることもできます：

```xml
    <taintAnalysis>         <ignoreFiles>             <directory name="tests"/>         </ignoreFiles>     </taintAnalysis>
```

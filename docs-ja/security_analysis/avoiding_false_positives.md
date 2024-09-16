# 偽陽性を避ける

Psalmのtaint分析を初めて実行すると、多くの偽陽性が表示される場合があります。
誰も偽陽性は好みません！

これらを防ぐ方法がいくつかあります：

## 汚染された入力をエスケープする

一部の操作はデータから汚染を除去します - 例えば、`$_GET['name']`を`htmlentities`呼び出しでラップすると、その`$_GET`呼び出しのクロスサイトスクリプティング攻撃を防ぎます。

Psalmでは、`@psalm-taint-escape <taint-type>`アノテーションを使用して汚染を除去できます：

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

## 条件付きで汚染された入力をエスケープする

前の例を少し修正したバージョンでは、条件を使用して戻り値が安全とみなされるかどうかを判断します。関数の引数`$escape`がtrueの場合にのみ、対応する`@psalm-taint-escape`アノテーションが汚染タイプ`html`に適用されます。

```php
<?php
/**
 * @param string $str
 * @param bool $escape
 * @psalm-taint-escape ($escape is true ? 'html' : null)
 */
function processVar(string $str, bool $escape = true) : string {
    if ($escape) {
      $str = str_replace(['<', '>'], '', $str);
    }
    return $str;
}

echo processVar($_GET['text'], false); // 汚染されたHTMLを検出
echo processVar($_GET['text'], true);  // 安全とみなされる
```

## ユーザー入力のHTMLをサニタイズする

可能な限り、アプリケーションはユーザー入力をHTMLブロックではなく、個別のテキストフィールドとして受け入れて保存するように設計されるべきです。これにより、ユーザー入力を`htmlspecialchars`や`htmlentities`を使用して完全にエスケープできます。HTMLユーザー入力が必要な場合（例：[TinyMCE](https://www.tiny.cloud/)のようなリッチテキストエディタ）、リスクのあるHTMLをフィルタリングするために特別に設計されたライブラリの使用を強く推奨します。例えば、[HTML Purifier](http://htmlpurifier.org/docs)は次のように使用できます：

```php
<?php
/**
 * @psalm-taint-escape html
 * @psalm-taint-escape has_quotes
 */
function sanitizeHTML($html){
    $purifier = new HTMLPurifier();
    return $purifier->purify($html);
}
```

## 関数内の汚染を特殊化する

関数、メソッド、クラスに対しては、`@psalm-taint-specialize`アノテーションを使用できます。

```php
<?php
function takesInput(string $s) : string {
    return $s;
}

echo htmlentities(takesInput($_GET["name"]));
echo takesInput("hello"); // Psalmはここで汚染されたHTMLを検出します
```

`@psalm-taint-specialize`アノテーションを追加することで問題が解決します。これは、関数の各呼び出しを別々に扱うようPsalmに指示します。

```php
<?php
/**
 * @psalm-taint-specialize
 */
function takesInput(string $s) : string {
    return $s;
}

echo htmlentities(takesInput($_GET["name"]));
echo takesInput("hello"); // エラーなし
```

特殊化された関数やメソッドは、依然として汚染された入力を追跡します：

```php
<?php
/**
 * @psalm-taint-specialize
 */
function takesInput(string $s) : string {
    return $s;
}

echo takesInput($_GET["name"]); // Psalmは汚染された入力を検出します
echo takesInput("hello"); // エラーなし
```

ここでは、関数の汚染状態が完全に関数への入力に依存していることをPsalmに伝えています。

[Psalmにおける不変性](https://psalm.dev/articles/immutability-and-beyond)に精通している場合、この一般的な考え方は馴染みがあるはずです。純粋関数は出力が完全に入力に依存するものだからです。驚くべきことではありませんが、`@psalm-pure`とマークされたすべての関数も、入力に基づいて出力の汚染状態を特殊化します：

```php
<?php
/**
 * @psalm-pure
 */
function takesInput(string $s) : string {
    return $s;
}

echo htmlentities(takesInput($_GET["name"]));
echo takesInput("hello"); // エラーなし
```

## クラス内の汚染を特殊化する

関数呼び出しで汚染を特殊化できるのと同様に、汚染されたプロパティも特定のクラスに特殊化できます。

```php
<?php
class User {
    public string $name;

    public function __construct(string $name) {
        $this->name = $name;
    }
}

/**
 * @psalm-taint-specialize
 */
function echoUserName(User $user) {
    echo $user->name; // エラー、汚染された入力を検出
}

$user1 = new User("Keith");
$user2 = new User($_GET["name"]);
echoUserName($user1);
```

クラスに`@psalm-taint-specialize`を追加することで問題が解決します。

```php
<?php
/**
 * @psalm-taint-specialize
 */
class User {
    public string $name;

    public function __construct(string $name) {
        $this->name = $name;
    }
}

/**
 * @psalm-taint-specialize
 */
function echoUserName(User $user) {
    echo $user->name; // エラーなし
}

$user1 = new User("Keith");
$user2 = new User($_GET["name"]);
echoUserName($user1);
```

そして、これは一種の純粋性の強制なので、`@psalm-immutable`も使用できます：

```php
<?php
/**
 * @psalm-immutable
 */
class User {
    public string $name;

    public function __construct(string $name) {
        $this->name = $name;
    }
}

/**
 * @psalm-taint-specialize
 */
function echoUserName(User $user) {
    echo $user->name; // エラーなし
}

$user1 = new User("Keith");
$user2 = new User($_GET["name"]);
echoUserName($user1);
```

## 汚染パスでファイルを避ける

特定のファイルやディレクトリを通過する汚染パスに興味がないことをPsalmに伝えることもできます。Psalm設定で以下のように指定します：

```xml
    <taintAnalysis>
        <ignoreFiles>
            <directory name="tests"/>
        </ignoreFiles>
    </taintAnalysis>
```

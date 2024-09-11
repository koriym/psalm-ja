# パラメータ名の不一致

メソッドが親メソッドをオーバーライドする際に、パラメータ名を変更した場合に発生します。

```php
<?php

class A {
    public function foo(string $str, bool $b = false) : void {}
}

class AChild extends A {
    public function foo(string $string, bool $b = false) : void {}
}
```

## なぜこれが悪いのか？

PHP 8 では[named parameters](https://wiki.php.net/rfc/named_params) が導入され、明示的に名前を指定したパラメータで メソッドをコールできるようになりました；

```php
<?php

function callFoo(A $a) {
    $a->foo(str: "hello");
}
```

最初の例では、`new AChild()` を`callFoo()` に渡すと致命的なエラーになります。AChild のメソッド`foo()` の定義には、`$str` という名前のパラメータがないからです。

## 修正方法

子メソッドのパラメータ名を変更することで対応できます：

```php
<?php

class A {
    public function foo(string $str, bool $b = false) : void {}
}

class AChild extends A {
    public function foo(string $str, bool $b = false) : void {}
}
```

この修正[can be applied automatically by Psalter](https://psalm.dev/docs/manipulating_code/fixing/#paramnamemismatch).

## 回避策

### @no-named-引数

あるいは、親メソッドに`@no-named-arguments` アノテーションを追加することで、この問題を無視することもできます：

```php
<?php

class A {
    /** @no-named-arguments */
    public function foo(string $str, bool $b = false) : void {}
}

class AChild extends A {
    public function foo(string $string, bool $b = false) : void {}
}
```

このアノテーションを持つメソッドは、（Psalmによって）名前付きパラメータで呼び出されなくなります。

### コンフィグ allowNamedArgumentCalls="false"

これは、コードベースで名前付きパラメータを使用できないようにします。自己完結型のプロジェクトでは理想的ですが、ライブラリではあまり理想的ではありません。

つまり、`A` クラスが Psalm がスキャンできるディレクトリで定義されている限り、上記の元のコードはエラーを出しません。

### コンフィグ allowInternalNamedArgumentCalls="false"

ライブラリの作者のために、Psalmは、`@internal` クラスやメソッドでの名前付きパラメータ呼び出しを禁止する、より微妙なフラグをサポートしています。

この設定値では、これは許可されます：

```php
<?php

/**
 * @internal
 */
class A {
    public function foo(string $str, bool $b = false) : void {}
}

class AChild extends A {
    public function foo(string $string, bool $b = false) : void {}
}
```

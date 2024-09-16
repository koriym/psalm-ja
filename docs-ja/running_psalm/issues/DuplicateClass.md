# DuplicateClass
クラスが2回定義された場合に発生します。

```php
<?php
class A {}
class A {}
```

## なぜこれが問題なのか
上記のコードはコンパイルされません。

PHPでは条件付きでクラスを定義することは可能です：

```php
<?php
if (rand(0, 1)) {
    class A {
        public function __construct(string $s) {}
    }
} else {
    class A {
        public function __construct(object $o) {}
    }
}
```

しかし、Psalmはこのパターンを使用したくありません - Psalmは（リフレクションを使用せずに）どのクラスが使用されているかを知ることは不可能です。

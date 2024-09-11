# 複製クラス

クラスが二重に定義されている場合に発せられる

```php
<?php

class A {}
class A {}
```

## なぜこれが悪いのか

上記のコードはコンパイルできない。

PHP では、クラスを条件付きで定義することができます：

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

しかし、Psalmはこのパターンを使ってほしくないのです。Psalmは（リフレクションを使わなければ）どのクラスが使われているかを知ることができないからです。

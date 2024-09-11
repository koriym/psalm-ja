# 未使用の可能性があるパラメータ

`--find-dead-code` が有効になっており、Psalm が public/protected メソッドで特定のパラメータの使用を見つけられなかった場合に発行されます。

```php
<?php

class A {
    public function foo(int $a, int $b) : int {
        return $a + 4;
    }
}

$a = new A();
echo $a->foo(1, 2);
```

パラメータ名の前にアンダースコアを付けることで抑制できる：

```php
<?php
class A {
    public function foo(int $a, int $_b) : int {
        return $a + 4;
    }
}
```

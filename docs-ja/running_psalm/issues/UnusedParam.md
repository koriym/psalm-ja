# 未使用パラメータ

`--find-dead-code` が有効になっており、Psalm がプライベートメソッドまたは関数内で特定のパラメータを使用する方法を見つけられなかった場合に発行されます。

```php
<?php

function foo(int $a, int $b) : int {
    return $a + 4;
}
```

パラメータ名の前にアンダースコアを付けることで抑制できる：

```php
function foo(int $_a, int $b) : int {
    return $b + 4;
}
```

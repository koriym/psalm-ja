# 未使用リーチ値

`--find-dead-code` がオンになっており、Psalm が foreach 値への参照を見つけられない場合に発行されます。

```php
<?php

/** @param array<string, int> $a */
function foo(array $a) : void {
    foreach ($a as $key => $value) { // $value is unused
        echo $key;
    }
}
```

変数名の前にアンダースコアを付けるか、`$_` という名前を付けることで抑制できる：

```php
<?php

foreach ([1, 2, 3] as $key => $_val) {}

foreach ([1, 2, 3] as $key => $_) {}
```

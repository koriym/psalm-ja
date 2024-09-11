混乱を招くスコープからの再利用 # ReferenceReusedFromConfusingScope

混乱させる可能性のあるスコープで代入された参照が、 後で再利用されたときに発せられます。PHP はループや if を他の多くの言語と同じようにスコープしないので、 よくある問題はそのようなスコープで宣言された変数の再利用です。すでに定義されている変数への再代入は単に再代入されるだけなので、通常は問題になりませんが、 すでに定義されている変数が参照である場合は、参照先の変数の値が変更されます。

```php
<?php

$arr = [1, 2, 3];
foreach ($arr as &$i) {
    ++$i;
}

// ...more code, after which the usage of $i as a reference has been forgotten by the programmer

for ($i = 0; $i < 10; ++$i) {
    echo $i;
}
// $arr is now [2, 3, 10] instead of the expected [2, 3, 4]
```

この問題を解決するには、そのようなスコープで参照変数を使用した後、参照の設定を解除してください：

```php
<?php

$arr = [1, 2, 3];
foreach ($arr as &$i) {
    ++$i;
}
unset($i);

for ($i = 0; $i < 10; ++$i) {
    echo $i;
}
// $arr is correctly [2, 3, 4]
```

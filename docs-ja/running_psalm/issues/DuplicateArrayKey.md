# 複製配列キー

配列のキーが複数回ある場合に発せられる

```php
<?php

$arr = [
    'a' => 'one',
    'b' => 'two',
    'c' => 'this text will be overwritten by the next line',
    'c' => 'three',
];
```

これは、`@no-named-arguments` が指定されていない場合、可変個引数によって引き起こされる可能性があります：

```php
<?php
function foo($bar, ...$baz): array
{
    return [$bar, ...$baz]; // $baz is array<array-key, mixed> since it can have named arguments
}
```

## 修正方法

重複を削除する：

```php
<?php

$arr = [
    'a' => 'one',
    'b' => 'two',
    'c' => 'three',
];
```

動作の変化を防ぐため、最初にマッチした`'c'` キーを削除した（新しい重複キーは以前のキーの値を上書きする）。

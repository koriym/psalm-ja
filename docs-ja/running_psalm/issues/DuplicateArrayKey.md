# DuplicateArrayKey
配列に同じキーが複数回ある場合に発生します。

```php
<?php
$arr = [
    'a' => 'one',
    'b' => 'two',
    'c' => 'このテキストは次の行で上書きされます',
    'c' => 'three',
];
```

これは、`@no-named-arguments`が指定されていない場合、可変引数によって引き起こされる可能性があります：

```php
<?php
function foo($bar, ...$baz): array {
    return [$bar, ...$baz]; // $bazは名前付き引数を持つ可能性があるため、array<array-key, mixed>です
}
```

## 修正方法
問題のある重複を削除します：

```php
<?php
$arr = [
    'a' => 'one',
    'b' => 'two',
    'c' => 'three',
];
```

最初に一致する'c'キーを削除して、動作の変更を防ぎました（新しい重複キーは以前の値を上書きします）。

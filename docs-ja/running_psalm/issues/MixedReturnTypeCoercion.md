# MixedReturnTypeCoercion
Psalmが配列/イテラブルの戻り値型の制約の一部が満たされることを確信できない場合に発生します。

```php
<?php
/** @return string[] */
function foo(array $a) : array {
    return $a;
}
```

これは、`@no-named-arguments`が指定されていない場合、可変引数で発生する可能性があります：

```php
<?php
/** @return list<int> */
function foo(int ...$args): array {
    return $args; // $argsは名前付き引数を持つ可能性があるため、array<array-key, int>です
}
```

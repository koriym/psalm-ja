# 返り値混合型コーエーション

Psalmが、配列/反復処理可能な戻り値の型の制約の一部が満たされることを確信できない場合に発行されます。

```php
<?php

/**
 * @return string[]
 */
function foo(array $a) : array {
    return $a;
}
```

これは、`@no-named-arguments` が指定されていない場合、可変個引数で発生する可能性があります：

```php
<?php

/** @return list<int> */
function foo(int ...$args): array {
    return $args; // $args is array<array-key, int> since it can have named arguments
}
```

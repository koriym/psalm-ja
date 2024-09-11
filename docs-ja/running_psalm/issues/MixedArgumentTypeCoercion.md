# 混合引数型強制引数

Psalmが配列/イテレータブル引数の型制約の一部が満たされることを確信できない場合に発行される。

```php
<?php

function foo(array $a) : void {
    takesStringArray($a);
}

/** @param string[] $a */
function takesStringArray(array $a) : void {}
```

これは、`@no-named-arguments` が指定されていない場合、可変個引数で発生する可能性があります：

```php
<?php

/** @param list<int> $args */
function foo(int ...$args): array {
    return $args; // $args is array<array-key, int> since it can have named arguments
}
```

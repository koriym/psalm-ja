# FalsableReturnStatement
関数の戻り値の型がfalseを許可していないにもかかわらず、return文にfalse値が含まれている場合に発生します。

```php
<?php
function getCommaPosition(string $a) : int {
    return strpos($a, ',');
}
```

## 修正方法
falseに対する具体的なチェックを追加することができます：

```php
<?php
function getCommaPosition(string $a) : int {
    $pos = strpos($a, ',');
    if ($pos === false) {
        return -1;
    }
    return $pos;
}
```

あるいは、例外をスローすることを選択することもできます：

```php
<?php
function getCommaPosition(string $a) : int {
    $pos = strpos($a, ',');
    if ($pos === false) {
        throw new Exception('This is unexpected');
    }
    return $pos;
}
```

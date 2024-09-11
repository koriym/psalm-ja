# FalsableReturnStatement

戻り値に偽の値が含まれているが、関数の戻り値の型が偽を許可していない場合に発行されます。

```php
<?php

function getCommaPosition(string $a) : int {
    return strpos($a, ',');
}
```

## 修正方法

falseのチェックを追加する：

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

あるいは、例外をスローすることもできます：

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

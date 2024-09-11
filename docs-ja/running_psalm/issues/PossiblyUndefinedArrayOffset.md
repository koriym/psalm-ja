# PossiblyUndefinedArrayOffset（未定義配列オフセットの可能性

未定義の可能性がある配列オフセットにアクセスしようとしたときに返されます。

```php
<?php

if (rand(0, 1)) {
    $arr = ["a" => 1, "b" => 2];
} else {
    $arr = ["a" => 3];
}

echo $arr["b"];
```

## 修正方法

null coalesce演算子を使用すると、配列オフセットが存在しない場合のデフォルト値を指定することができます：

```php
<?php

...

echo $arr["b"] ?? 0;
```

あるいは、配列のオフセットが常に存在するようにすることもできます：

```php
<?php

if (rand(0, 1)) {
    $arr = ["a" => 1, "b" => 2];
} else {
    $arr = ["a" => 3, "b" => 0];
}

echo $arr["b"];
```

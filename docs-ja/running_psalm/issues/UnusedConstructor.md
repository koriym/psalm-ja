# 未使用コンストラクタ

`--find-dead-code` が有効になっており、Psalm が指定されたプライベートコンストラクタまたは関数の使用を見つけられなかった場合に発行されます。

```php
<?php

class A {
    private function __construct() {}

    public static function createInstance() : void {}
}
A::createInstance();
```

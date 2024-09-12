# UnusedConstructor
`--find-dead-code`がオンになっていて、Psalmが特定のプライベートコンストラクタまたは関数の使用を見つけられない場合に発生します。

```php
<?php
class A {
    private function __construct() {}
    public static function createInstance() : void {}
}
A::createInstance();
```

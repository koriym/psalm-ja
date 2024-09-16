# UnusedMethodCall
`--find-dead-code`がオンになっていて、Psalmが戻り値がどこにも使用されていないメソッド呼び出しを見つけた場合に発生します。

```php
<?php
final class A {
    private string $foo;
    public function __construct(string $foo) {
        $this->foo = $foo;
    }
    public function getFoo() : string {
        return $this->foo;
    }
}

$a = new A("hello");
$a->getFoo();
```

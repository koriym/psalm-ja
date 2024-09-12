# UninitializedProperty
コンストラクタ内でプロパティが初期化される前に使用された場合に発生します。

```php
<?php
class A {
    /** @var string */
    public $foo;

    public function __construct() {
        echo strlen($this->foo);
        $this->foo = "foo";
    }
}
```

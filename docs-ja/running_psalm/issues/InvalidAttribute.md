# 無効属性

属性のターゲットと一致しない要素で属性を使用したときに発せられる。

```php
<?php
namespace Foo;

#[\Attribute(\Attribute::TARGET_CLASS)]
class Table {
    public function __construct(public string $name) {}
}

#[Table("videos")]
function foo() : void {}
```

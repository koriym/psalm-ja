# 未使用プロパティ

`--find-dead-code` がオンになっており、Psalm がプライベート・プロパティの使用を見つけられなかった場合に発行されます。

コンストラクタでのみ使用されるプロパティは未使用とみなされます。代わりに通常の変数を使用してください。

このプロパティが使用され、パブリック API の一部となっている場合は、`@psalm-api` を含むクラスにアノテーションしてください。

```php
<?php

class A {
    /** @var string|null */
    private $foo;

    /** @var int|null */
    private $bar;

    public function getFoo(): ?string {
        return $this->foo;
    }
}

$a = new A();
echo $a->getFoo();
```

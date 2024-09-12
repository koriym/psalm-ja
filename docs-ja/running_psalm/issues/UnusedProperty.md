# UnusedProperty
`--find-dead-code`がオンになっていて、Psalmがプライベートプロパティの使用を見つけられない場合に発生します。
コンストラクタでのみ使用されるプロパティは未使用とみなされます。代わりに通常の変数を使用してください。
このプロパティが使用されており、公開APIの一部である場合は、含むクラスに`@psalm-api`でアノテーションを付けてください。

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

# PropertyNotSetInConstructor
初期化されていないプロパティは静的に分析するのが難しいです。ミスを防ぐために、Psalmはすべてのプロパティが初期化されるべきであることを強制します。
これは[MissingConstructor](./MissingConstructor.md)とこの問題を通じて行われます。
その後、Psalmはコードベース内のすべてのプロパティが初期化されていると仮定します。
これにより、初期化の欠如を報告するだけでなく、[RedundantPropertyInitializationCheck](./RedundantPropertyInitializationCheck.md)も報告できるようになります。
この問題は、デフォルト値を持たない非nullプロパティがクラスのコンストラクタで設定されていない場合に発生します。
プロジェクトが初期化されていないプロパティに依存している場合は、この問題を抑制し、同様に[MissingConstructor](./MissingConstructor.md)と[RedundantPropertyInitializationCheck](./RedundantPropertyInitializationCheck.md)も抑制することをお勧めします。

```php
<?php
class A {
    /** @var string */
    public $foo;

    public function __construct() {}
}
```

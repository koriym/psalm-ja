# PropertyNotSetInConstructor（プロパティ・ノットセット・イン・コンストラクタ

初期化されていないプロパティを静的に解析するのは困難です。間違いを防ぐために、Psalmはすべてのプロパティを初期化することを強制します。

これは、[MissingConstructor](./MissingConstructor.md) とこの問題を通して行います。

Psalmはコードベース内のすべてのプロパティが初期化されていると仮定します。

そうすることで、初期化の欠落を報告することができます。[RedundantPropertyInitializationCheck](./RedundantPropertyInitializationCheck.md)

この問題は、デフォルト値のない非 null プロパティが宣言されているが、クラスのコンストラクタで設定されていない場合に発生します。

初期化されていないプロパティの存在に依存しているプロジェクトでは、[MissingConstructor](./MissingConstructor.md) や[RedundantPropertyInitializationCheck](./RedundantPropertyInitializationCheck.md) と同様に、この問題を抑制することをお勧めします。

```php
<?php

class A {
    /** @var string */
    public $foo;

    public function __construct() {}
}
```

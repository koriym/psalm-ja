# MissingConstructor

ユニット化されたプロパティは静的に解析しにくい。間違いを防ぐために、Psalmはすべてのプロパティを初期化することを強制します。

これは、[PropertyNotSetInConstructor](./PropertyNotSetInConstructor.md) とこの問題を通して行われます。

Psalmはコードベース内のすべてのプロパティが初期化されていると仮定します。

そうすることで、初期化の欠落を報告することができます。[RedundantPropertyInitializationCheck](./RedundantPropertyInitializationCheck.md)

この問題は、デフォルト値のないnullでないプロパティが`__construct` メソッドのないクラスで定義されている場合に発生します。

初期化されていないプロパティを持つことに依存しているプロジェクトでは、[PropertyNotSetInConstructor](./PropertyNotSetInConstructor.md) や[RedundantPropertyInitializationCheck](./RedundantPropertyInitializationCheck.md) と同様に、この問題を抑制することをお勧めします。

```php
<?php

class A {
    /** @var string */
    public $foo;
}
```

# RedundantPropertyInitializationCheck
初期化されていないプロパティは静的に分析するのが難しいです。ミスを防ぐために、Psalmはすべてのプロパティが初期化されるべきであることを強制します。
これは[PropertyNotSetInConstructor](./PropertyNotSetInConstructor.md)と[MissingConstructor](./MissingConstructor.md)を通じて行われます。
その後、Psalmはコードベース内のすべてのプロパティが初期化されていると仮定します。
これにより、初期化の欠如を報告するだけでなく、この問題も報告できるようになります。
この問題は、非nullableプロパティに対して`isset()`をチェックする場合に発生します。すべてのプロパティが初期化されていると仮定されるため、このチェックは冗長です。
プロジェクトが初期化されていないプロパティに依存している場合は、この問題を抑制し、同様に[PropertyNotSetInConstructor](./PropertyNotSetInConstructor.md)と[MissingConstructor](./MissingConstructor.md)も抑制することをお勧めします。

```php
<?php
    class A {
        public string $bar;
        public function getBar() : string {
            if (isset($this->bar)) {
                return $this->bar;
            }
            return "hello";
        }
    }
```

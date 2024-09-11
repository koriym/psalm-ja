# 冗長プロパティ初期化チェック

ユニット化されたプロパティは静的に分析するのが難しい。間違いを防ぐために、Psalmはすべてのプロパティを初期化するように強制します。

これは[PropertyNotSetInConstructor](./PropertyNotSetInConstructor.md) と[MissingConstructor](./MissingConstructor.md) を通して行います。

Psalmはコードベース内のすべてのプロパティが初期化されていると仮定します。

そうすることで、この問題と同様に、初期化の欠落を報告することができます。

この問題は、nullableでないプロパティで`isset()` 。すべてのプロパティが初期化されていると仮定されているため、このチェックは冗長です。

初期化されていないプロパティを持つことに依存しているプロジェクトでは、[PropertyNotSetInConstructor](./PropertyNotSetInConstructor.md) や[MissingConstructor](./MissingConstructor.md) と同様に、この問題を抑制することをお勧めします。

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

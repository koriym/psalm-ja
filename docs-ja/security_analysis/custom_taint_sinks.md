# カスタムテイントシンク

この `@psalm-taint-sink <taint-type> <param-name>`アノテーションでテイントシンクを定義できます。

指定された[taint type](index.md#taint-types) にマッチする汚染された値は、 Psalm によってエラーとして報告されます。

### 例

ここでは、`PDOWrapper` クラスは汚染された SQL を受け取ってはならない`exec` メソッドを持っています：

```php
<?php

class PDOWrapper {
    /**
     * @psalm-taint-sink sql $sql
     */
    public function exec(string $sql) : void {}
}
```

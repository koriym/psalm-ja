# カスタム汚染シンク

`@psalm-taint-sink <taint-type> <param-name>`アノテーションを使用して、汚染シンクを定義できます。
指定された[汚染タイプ](index.md#taint-types)に一致する汚染値は、Psalmによってエラーとして報告されます。

### 例

ここでは、`PDOWrapper`クラスに汚染されたSQLを受け取るべきではない`exec`メソッドがあるため、その挿入を防ぐことができます：

```php
<?php
class PDOWrapper {
    /**
     * @psalm-taint-sink sql $sql
     */
    public function exec(string $sql) : void {}
}
```

# InvalidNamedArgument
提供された関数/メソッドの引数名が関数/メソッドのシグネチャと互換性がない場合に発生します。

```php
<?php
function takesArguments(string $name, int $age) : void {}
takesArguments(name: "hello", ag: 5);
```

# InvalidParamDefault

関数のパラメータのデフォルトが、Psalm が期待する型と衝突した場合に発生します。

```php
<?php

function foo(int $i = false) : void {}
```

# InvalidPassByReference

バイリファレンス変数を期待する関数に変数以外を渡したときに発行されます。

```php
<?php

function foo(array &$arr) : void {}
foo([0, 1, 2]);
```

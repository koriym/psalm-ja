# 混合論証

Psalmが引数の型を判断できない場合に発せられる。

```php
<?php

function takesInt(int $i) : void {}
takesInt($GLOBALS['foo']);
```

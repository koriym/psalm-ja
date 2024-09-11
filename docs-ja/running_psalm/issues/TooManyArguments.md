# 議論が多すぎる

関数の引数を超える引数で関数を呼び出したときに発生するエラー

```php
<?php

function foo(string $a) : void {}
foo("hello", 4);
```

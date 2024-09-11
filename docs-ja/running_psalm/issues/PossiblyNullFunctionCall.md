# PossiblyNullFunctionCall

NULLである可能性のある値に対して関数を呼び出そうとしたときに発せられる

```php
<?php

function foo(?callable $a) : void {
    $a();
}
```

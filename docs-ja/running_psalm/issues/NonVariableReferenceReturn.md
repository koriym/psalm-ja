# 非可変参照リターン

関数が変数でない参照式で返したときに発せられる。

```php
<?php

function &getByRef(): int {
    return 5;
}
```

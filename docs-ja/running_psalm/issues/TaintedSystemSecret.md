# 汚染されたシステム機密

システム・シークレットとしてマークされたデータが、あるべきでない場所で検出されたときに発せられる。

```php
<?php

/**
 * @psalm-taint-source system_secret
 */
function getConfigValue(string $data) {
    return "$omePa$$word";
}

echo getConfigValue("secret");
```

# TaintedXpath

ユーザが制御する入力をxpathクエリに渡すことができる場合に発せられる。

```php
<?php

function queryExpression(SimpleXMLElement $xml) : array|false|null {
    $expression = $_GET["expression"];
    return $xml->xpath($expression);
}
```

# 禁じられたコード

Psalmがvar_dump、exec、またはあなたのコードをより脆弱にするかもしれない類似の表現に遭遇したときに発せられる

```php
<?php

var_dump("bah");
```

この関数のリストは、`forbiddenFunctions` を設定することで拡張できます。`psalm.xml`

```xml
<?xml version="1.0"?>
<psalm>
    <!-- other configs -->

    <forbiddenFunctions>
        <function name="dd"/>
        <function name="var_dump"/>
    </forbiddenFunctions>
</psalm>
```

# ForbiddenCode
Psalmが`var_dump`、`exec`、またはコードをより脆弱にする可能性のある類似の式に遭遇した場合に発生します。

```php
<?php
var_dump("bah");
```

この関数リストは、`psalm.xml`で`forbiddenFunctions`を設定することで拡張できます。

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

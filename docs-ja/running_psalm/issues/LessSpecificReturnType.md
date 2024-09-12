# LessSpecificReturnType
戻り値の型が関数自体よりも多くの可能性をカバーしている場合に発生します。

```php
<?php
function foo() : ?int {
    return 5;
}
```

# UndefinedDocblockClass
存在しないクラスをdocblockから参照しようとした場合に発生します。

```php
<?php
/** 
 * @param DoesNotExist $a 
 */
function foo($a) : void {}
```

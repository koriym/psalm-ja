# InvalidOperand
予期しないものをオペランドとして使用した場合に発生します。

```php
<?php
class A {}
echo (new A) . ' ';
```

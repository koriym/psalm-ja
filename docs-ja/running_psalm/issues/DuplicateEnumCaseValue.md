# 重複EnumCaseValue

バックされた列挙型のケース値が重複している場合に発行されます。

```php
<?php

enum Status: string 
{
    case Open = "open";
    case Closed = "open";
}
```

## 修正方法

大文字と小文字が重複しないように変更する。

```php
<?php

enum Status: string 
{
    case Open = "open";
    case Closed = "closed";
}
```

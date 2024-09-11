# 重複EnumCase

enumが重複している場合に発行されます。

```php
<?php

enum Status 
{
    case Open;
    case Open;
}
```

## 修正方法

重複しているファイルを削除するか、名前を変更する。

```php
<?php

enum Status 
{
    case Open;
    case Closed;
}
```

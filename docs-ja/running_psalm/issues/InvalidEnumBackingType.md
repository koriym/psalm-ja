# 無効なEnumBackingType

列挙型は、`int` または`string` によってのみバッキングできます。 列挙型が他の何かによってバッキングされている場合に発行されます。

```php
<?php

enum Status: array 
{
   case None = [];
}
```

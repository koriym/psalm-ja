# InvalidEnumBackingType
列挙型は`int`または`string`でのみバックアップできます。列挙型が他のもので
バックアップされている場合に発生します。

```php
<?php
enum Status: array {
   case None = [];
}
```

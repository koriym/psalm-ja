# DuplicateEnumCaseValue
バックド列挙型に重複するケース値がある場合に発生します。

```php
<?php
enum Status: string {
    case Open = "open";
    case Closed = "open";
}
```

## 修正方法
重複がないようにケース値を変更します。

```php
<?php
enum Status: string {
    case Open = "open";
    case Closed = "closed";
}
```

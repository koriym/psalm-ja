# 無効なEnumCaseValue

大文字小文字の値が無効な場合に発行されます(下記参照)。

## 純粋な列挙型の値を持つケース

```php
<?php

enum Status 
{
    case Open = "open";
}
```

### 修正方法

値を削除するか、バックアップする列挙型を変更する。

```php
<?php

enum Status: string 
{
    case Open = "open";
}
```

## バックされる列挙型に値がない場合

```php
<?php

enum Status: string 
{
    case Open;    
}
```

### 修正方法

列挙型を純粋なものに変更するか、値を追加する。

```php
<?php

enum Status 
{
    case Open;
}
```

## 型の不一致

ケース型は列挙型のバッキング型と一致する必要があります。

```php
<?php

enum Status: string
{
    case Open = 1;
}
```

### 修正方法

タイプが一致するように変更する

```php
<?php

enum Status: string 
{
    case Open = "open";
}
```

## 列挙型を返せない型の場合

ケースの型は`int` または`string` のどちらかでなければならない。

```php
<?php

enum Status: int {
    case Open = [];
}
```

### 修正方法

大文字と小文字の値を変更する。

```php
<?php

enum Status: int
{
    case Open = 1;
}
```

# ImpureByReferenceAssignment
mutation-freeとマークされた関数やメソッド内で、参照渡しされた変数に代入しようとした場合に発生します。

```php
<?php
/** 
 * @psalm-pure 
 */
function foo(string &$a): string {
    $a = "B";
    return $a;
}
```

## 修正方法
変更を加える代入を削除するだけです：

```php
<?php
/** 
 * @psalm-pure 
 */
function foo(string &$a): string {
    return $a;
}
```

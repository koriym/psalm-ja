# ArgumentTypeCoercion
関数のシグネチャやdocblockの型よりも具体的でない型の引数を関数に渡した場合に発生します。

```php
<?php
class A {}
class B extends A {}

function foo(A $a) : void {
    takesB($a);
}

function takesB(B $b) : void {}
```

## なぜこれが問題なのか
不正な値で関数を呼び出すと、実行時に致命的なエラーが発生します。

## 修正方法
`takesB`の呼び出しの前に型チェックを追加することができます：

```php
<?php
function foo(A $a) : void {
    if ($a instanceof B) {
        takesB($a);
    }
}
```

または、`foo`の関数シグネチャを変更する権限がある場合は、`B`を期待するように変更できます：

```php
<?php
function foo(B $a) : void {
    takesB($a);
}
```

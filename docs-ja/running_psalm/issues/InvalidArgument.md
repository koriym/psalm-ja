# InvalidArgument
提供された関数/メソッドの引数が、メソッドのシグネチャまたはdocblockと互換性がない場合に発生します。

```php
<?php
class A {}

function foo(A $a) : void {}

/**
 * @param string $s
 */
function callFoo($s) : void {
    foo($s);
}
```

## なぜこれが問題なのか
不正な値で関数を呼び出すと、実行時に致命的なエラーが発生します。

## 修正方法
この問題は、単に不正確なdocblockの結果である場合があります。
docblockを修正するか、関数のシグネチャに変換することで修正できます：

```php
<?php
class A {}

function foo(A $a) : void {}

function callFoo(A $a) : void {
    foo($a);
}
```

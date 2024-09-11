# 無効な引数

提供された関数/メソッドの引数が、メソッドのシグネチャまたはdocblockのものと互換性がない場合に発行されます。

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

## なぜ悪いのか

間違った値で関数を呼び出すと、実行時に致命的なエラーが発生します。

## 修正方法

このメッセージは、単に間違ったdocblockの結果であることもあります。

docblockを修正するか、関数シグネチャに変換することで修正できます：

```php
<?php

class A {}

function foo(A $a) : void {}

function callFoo(A $a) : void {
    foo($a);
}
```

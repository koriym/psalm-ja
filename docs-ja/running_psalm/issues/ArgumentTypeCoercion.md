# ArgumentTypeCoercion

関数が期待する型よりも小さい型を持つ引数で関数を呼び出したときに発せられる。

```php
<?php

class A {}
class B extends A {}

function takesA(A $a) : void {
    takesB($a);
}
function takesB(B $b) : void {}
```

## 修正方法

`takesB` を呼び出す前にタイプチェックを追加する：

```php
<?php

function takesA(A $a) : void {
    if ($a instanceof B) {
        takesB($a);
    }
}
```

あるいは、`takesA` の関数シグネチャを制御できるなら、`B` を期待するように変更することもできる：

```php
<?php

function takesA(B $a) : void {
    takesB($a);
}
```

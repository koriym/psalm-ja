# AssignmentToVoid
`void`を返す関数から代入しようとした場合に発生します：

```php
<?php
function foo() : void {}
$a = foo();
```

## なぜこれが問題なのか
`void`を返す関数はPHPでは`null`を返すものとして扱われますが（そのため、これ自体はランタイムエラーにはつながりません）、`void`はプログラミング言語全般で代入目的で設計されていない概念です。

## 修正方法
代入を完全に削除するだけで修正できます：

```php
<?php
function foo() : void {}
foo();
```

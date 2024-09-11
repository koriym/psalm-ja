# ミックスメソッド・コール

Psalmが型を推測できない値に対してメソッドを呼び出したときに発せられる

```php
<?php

class A {
    public function foo() : void {}
}

function callFoo(array $arr) : void {
    array_pop($arr)->foo(); // MixedMethodCall emitted here
}

callFoo(
    [new A()]
);
```

## これが悪い理由

もしPsalmが`array_pop($arr)` が何であるかを知らなければ、`array_pop($arr)->foo()` が機能するかどうかを検証できない。

## 修正方法

Psalmが推論を実行できるように、できるだけ多くの型情報を提供するように してください。例えば、`callFoo` 関数にdocblockを追加することができます：

```php
<?php

class A {
    public function foo() : void {}
}

/**
 * @param  array<A> $arr
 */
function callFoo(array $arr) : void {
    array_pop($arr)->foo(); // MixedMethodCall emitted here
}

callFoo(
    [new A()]
);
```

あるいは、実行時チェックを追加することもできる：

```php
<?php

class A {
    public function foo() : void {}
}

function callFoo(array $arr) : void {
    $a = array_pop($arr);
    assert($a instanceof A);
    $a->foo(); // MixedMethodCall emitted here
}

callFoo(
    [new A()]
);
```

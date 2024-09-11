# 非可変DocblockPropertyType

public または protected クラスのプロパティが、親クラスの一致するプロパティと異なる docblock タイプを持つ場合に発行されます。

```php
<?php

class A {
    /** @var null|string */
    public $foo = 'hello';
}

class AChild extends A {
    /** @var string */
    public $foo;
}
```

## なぜこれが悪いのか

型付けされていないプロパティの場合、親クラスに対して書かれたコードが子クラスのオブジェクトの値を読み書きすると、型システム違反を引き起こす可能性があります。

子クラスが型の幅を広げている場合、値を読み取るとクライアントコードが対処できない予期せぬ値が返される可能性があります。子クラスが型を狭めている場合、値を設定するコードがその値を無効な値に設定する可能性があります：

```php
<?php

function takesA(A $a) {
    $a->foo = null; // this is valid for A
}

$child = new AChild();
takesA($child);
echo strlen($child->foo); // this is valid for AChild
```

## 回避策

型を広げるか、状況によっては代わりにテンプレートを使うこともできる：

```php
<?php

/**
 * @template T of string|null
 */
abstract class A {
    /** @var T */
    public $foo = 'hello';
}

/**
 * @extends A<string>
 */
class AChild extends A {
    /** @var string */
    public $foo;
}
```

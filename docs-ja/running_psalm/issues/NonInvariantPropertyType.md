# 非可変プロパティタイプ

public または protected クラスのプロパティが、親クラスの一致するプロパティと異なる型を持つ場合に発行されます。

```php
<?php

class A {
    public string $foo = 'hello';
}

class B extends A {
    public ?string $foo;
}

```

## なぜこれが悪いのか

型付きプロパティの場合、これはコンパイルエラーを引き起こす可能性があります。

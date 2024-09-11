# UnsafeInstantiation

`new static` などを使って、final コンストラクタのないクラスをインスタンス化しようとしたときに発行されます：

```php
<?php

class A {
    public function getInstance() : self
    {
        return new static();
    }
}
```

## どうしたんだ？

問題はクラスを拡張するときに起こります：

```php
<?php

class A {
    public function getInstance() : self
    {
        return new static();
    }
}

class AChild extends A {
    public function __construct(string $some_required_param) {}
}

AChild::getInstance(); // fatal error
```

## 修正方法

コンストラクタをfinalにする：

```php
<?php

class A {
    final public function __construct() {}

    public function getInstance() : self
    {
        return new static();
    }
}
```

あるいは、`@psalm-consistent-constructor` アノテーションを追加して、子クラスのコンストラクタが親コンストラクタと同じシグネチャを持つようにすることもできます：

```php
<?php

/**
 * @psalm-consistent-constructor
 */
class A {
    public function getInstance() : self
    {
        return new static();
    }
}

class AChild extends A {
    public function __construct() {
        // this is fine
    }
}

class BadAChild extends A {
    public function __construct(string $s) {
        // this is reported as a violation
    }
}
```

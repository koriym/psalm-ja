# UnsafeGenericInstantiation（安全でないジェネリックのインスタンス化

`new static` を使って、final コンストラクタのないクラスをインスタンス化しようとしたときに発行されます：

```php
<?php

/**
 * @template T
 * @psalm-consistent-constructor
 */
class Container {
    /**
     * @var T
     */
    public $t;

    /**
     * @param T $t
     */
    public function __construct($t) {
        $this->t = $t;
    }

    /**
     * @template U
     * @param U $u
     * @return static<U>
     */
    public function getInstance($u) : static
    {
        return new static($u);
    }
}
```


## どうしたんだ？

問題はクラスを拡張するときに起こります：

```php
<?php

/**
 * @template T
 * @psalm-consistent-constructor
 */
class Container {
    /**
     * @var T
     */
    public $t;

    /**
     * @param T $t
     */
    public function __construct($t) {
        $this->t = $t;
    }

    /**
     * @template U
     * @param U $u
     * @return static<U>
     */
    public function getInstance($u) : static
    {
        return new static($u);
    }
}

/**
 * @extends Container<string>
 */
class StringContainer extends Container {}

$c = StringContainer::getInstance(new stdClass());
// creates StringContainer<stdClass>, clearly invalid
```

## 修正方法

`new static` の代わりに`new self` を使う：

```php
<?php

/**
 * @template T
 * @psalm-consistent-constructor
 */
class Container {
    /**
     * @var T
     */
    public $t;

    /**
     * @param T $t
     */
    public function __construct($t) {
        $this->t = $t;
    }

    /**
     * @template U
     * @param U $u
     * @return self<U>
     */
    public function getInstance($u) : self
    {
        return new self($u);
    }
}
```

あるいは、どの子クラスも同じジェネリック・パラメータを持つことを保証する`@psalm-consistent-templates` アノテーションを追加することもできます：

```php
<?php

/**
 * @template T
 * @psalm-consistent-constructor
 * @psalm-consistent-templates
 */
class Container {
    /**
     * @var T
     */
    public $t;

    /**
     * @param T $t
     */
    public function __construct($t) {
        $this->t = $t;
    }

    /**
     * @template U
     * @param U $u
     * @return static<U>
     */
    public function getInstance($u) : static
    {
        return new static($u);
    }
}

/**
 * @template T
 * @psalm-extends Container<T>
 */
class LazyLoadingContainer extends Container {}
```

# AmbiguousConstantInheritance
定数が複数のソースから継承された場合に発生します。

```php
<?php
interface Foo {
    /** @var non-empty-string */
    public const CONSTANT='foo';
}

interface Bar {
    /**
     * @var non-empty-string
     */
    public const CONSTANT='bar';
}

interface Baz extends Foo, Bar {}
```

```php
<?php
interface Foo {
    /** @var non-empty-string */
    public const CONSTANT='foo';
}

class Bar {
    /** @var non-empty-string */
    public const CONSTANT='bar';
}

class Baz extends Bar implements Foo {}
```

# AmbiguousConstantInheritance（曖昧な定数継承

定数が複数のソースから継承されている場合に発行されます。

```php
<?php

interface Foo
{
    /** @var non-empty-string */
    public const CONSTANT='foo';
}

interface Bar
{
    /**
     * @var non-empty-string
     */
    public const CONSTANT='bar';
}

interface Baz extends Foo, Bar {}
```

```php
<?php

interface Foo
{
    /** @var non-empty-string */
    public const CONSTANT='foo';
}

class Bar
{
    /** @var non-empty-string */
    public const CONSTANT='bar';
}

class Baz extends Bar implements Foo {}
```

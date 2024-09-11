# UnusedMethodCall

`--find-dead-code` がオンになっており、Psalm が返り値がどこにも使われていないメソッドコールを見つけたときに発せられる。

```php
<?php

final class A {
    private string $foo;

    public function __construct(string $foo) {
        $this->foo = $foo;
    }

    public function getFoo() : string {
        return $this->foo;
    }
}

$a = new A("hello");
$a->getFoo();
```

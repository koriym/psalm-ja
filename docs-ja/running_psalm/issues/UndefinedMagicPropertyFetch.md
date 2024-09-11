# UndefinedMagicPropertyFetch

そのマジック・プロパティが定義されていないオブジェクトのプロパティを取得する際に発せられる

```php
<?php

/**
 * @property string $bar
 */
class A {
    public function __get(string $name) {
        return "cool";
    }
}
$a = new A();
echo $a->foo;
```

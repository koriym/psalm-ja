# UndefinedThisPropertyAssignment
オブジェクトのメソッド内で、存在しないプロパティに対してオブジェクトのプロパティを割り当てようとした場合に発生します。

```php
<?php
class A {
    function foo() {
        $this->foo = "bar";
    }
}
```

# UndefinedMagicPropertyFetch
定義されていないマジックプロパティを持つオブジェクトからプロパティを取得しようとした場合に発生します。

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

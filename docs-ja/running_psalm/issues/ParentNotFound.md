# ParentNotFound
親クラスを持たないクラス内で`parent::`を使用しようとした場合に発生します。

```php
<?php
class A {
  public function foo() : void {
    parent::foo();
  }
}
```

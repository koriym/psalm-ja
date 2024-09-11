# 親が見つからない

親クラスを持たないクラスで`parent::` を使用した場合に発行されます。

```php
<?php

class A {
  public function foo() : void {
    parent::foo();
  }
}
```

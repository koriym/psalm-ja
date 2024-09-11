# ♪ ImplementedReturnTypeMismatch

他のクラスを継承したクラスや、インターフェイスを実装したクラスが、 docblockの戻り値の型が親クラスと全く異なる場合に発生します。 

```php
<?php

class A {
    /** @return bool */
    public function foo() {
        return true;
    }
}
class B extends A {
    /** @return string */
    public function foo()  {
        return "hello";
    }
}
```

## 修正方法

[Liskov substitution principle](https://en.wikipedia.org/wiki/Liskov_substitution_principle) 親メソッドをオーバーライドするメソッドは、必ず親メソッドのサブタイプを返さなければならない。

親メソッドをオーバーライドするメソッドは、親メソッドのサブタイプを返さなければならない。

```php
<?php

class A {
    /** @return bool|string */
    public function foo() {
        return true;
    }
}
class B extends A {
    /** @return string */
    public function foo()  {
        return "hello";
    }
}
```

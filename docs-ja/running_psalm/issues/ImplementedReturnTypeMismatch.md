# ImplementedReturnTypeMismatch
別のクラスを継承するクラス、またはインターフェースを実装するクラスが、親のものと全く異なるdocblock戻り値の型を持つ場合に発生します。

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
[リスコフの置換原則](https://en.wikipedia.org/wiki/Liskov_substitution_principle)を尊重するようにしてください - 親メソッドをオーバーライドするメソッドは、親メソッドのサブタイプを返す必要があります。
上記の場合、子の戻り値の型を親のものに追加することを意味します。

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

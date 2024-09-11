# ImplicitToStringCast

`__toString` メソッドを持つオブジェクトを暗黙的に文字列に変換する際に発せられる。

```php
<?php

class A {
    public function __toString() {
        return "foo";
    }
}

function takesString(string $s) : void {}

takesString(new A);
```

## 修正方法

明示的な文字列キャストを追加できる：

```php
<?php

...

takesString((string) new A);
```

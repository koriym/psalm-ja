# CircularReference
クラスが自身を親の1つとして参照している場合に発生します。

```php
<?php
class A extends B {}
class B extends A {}
```

## なぜこれが問題なのか
上記のコードはコンパイルされません。

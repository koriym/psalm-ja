# 無効な親

関数の戻り値の型が`parent` で、親クラスが存在しない場合に発生。
```php
<?php

class Foo {
    public function f(): parent {}
}
```

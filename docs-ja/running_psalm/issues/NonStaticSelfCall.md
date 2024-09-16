# NonStaticSelfCall
非静的関数を静的に呼び出そうとした場合に発生します。

```php
<?php
class A {
    public function foo(): void {}
    public static function bar(): void {
        self::foo();
    }
}
```

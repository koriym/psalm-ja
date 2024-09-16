# ImpureStaticProperty
純粋（pure）とマークされた関数やメソッドから静的プロパティを使用しようとした場合に発生します。

```php
<?php
class ValueHolder {
    public static ?string $value = null;

    /**
     * @psalm-pure
     */
    public static function get(): ?string {
        return self::$value;
    }
}
```

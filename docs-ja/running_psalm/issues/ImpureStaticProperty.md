# ImpureStaticProperty

純粋であるとマークされた関数やメソッドから静的プロパティを使用しようとしたときに発行されます。

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

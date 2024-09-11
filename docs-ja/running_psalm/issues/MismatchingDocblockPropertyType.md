#ミスマッチングDocblockPropertyType

プロパティのdocblock内の`@var` エントリがプロパティの型と一致しない場合に発行されます。

```php
<?php
class A {
    /** @var array */
    public string $s = [];
}
```

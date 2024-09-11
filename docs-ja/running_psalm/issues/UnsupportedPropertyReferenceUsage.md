# UnsupportedPropertyReferenceUsage（サポートされていないプロパティ参照使用法

Psalmは、プロパティへの参照を使用するコードの健全性を保証できません。

### キャッチされないエラーの例

* インスタンス・プロパティに間違った型が割り当てられています：
```php
<?php
class A {
    public int $b = 0;
}
$a = new A();
$b = &$a->b;
$b = ''; // Fatal error
```

* 静的プロパティに誤った型が割り当てられました：
```php
<?php
class A {
    public static int $b = 0;
}
$b = &A::$b;
$b = ''; // Fatal error
```

* 読み取り専用プロパティが再割り当てされました：
```php
<?php
class A {
    public function __construct(
        public readonly int $b,
    ) {
    }
}
$a = new A(0);
$b = &$a->b;
$b = 1; // Fatal error
```

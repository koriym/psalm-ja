# ExtensionRequirementViolation
トレイトを使用するクラスが、`@psalm-require-extends`を使用して指定されたクラスを拡張していない場合に発生します。

```php
<?php
class A { }

/**
 * @psalm-require-extends A
 */
trait T { }

class B {
  // ExtensionRequirementViolationが発生します。Tは
  // 使用クラスBがAを拡張することを要求していますが、
  // そうではありません
  use T;
}
```

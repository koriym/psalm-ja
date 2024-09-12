# PossiblyUnusedMethod
`--find-dead-code`がオンになっていて、Psalmがpublicまたはprotectedメソッドへの呼び出しを見つけられない場合に発生します。
このメソッドが使用されており、公開APIの一部である場合は、含むクラスに`@psalm-api`でアノテーションを付けてください。

```php
<?php
class A {
    public function foo() : void {}
    public function bar() : void {}
}
(new A)->foo();
```

# UnnecessaryVarAnnotation
`--find-dead-code`がオンになっていて、Psalmが既に型を識別している代入に対して`@var`アノテーションを使用している場合に発生します。

```php
<?php
function foo() : string {
    return "hello";
}

/** @var string */
$a = foo();
```

# 条件付き型

PsalmはTypeScriptの[条件付き型](https://www.typescriptlang.org/docs/handbook/advanced-types.html#conditional-types)に相当するものをサポートしています。

条件付き型は次の形式を取ります：

`(<template param> is <union type> ? <union type> : <union type>)`

すべての条件付き型は括弧で囲む必要があります。例：`(...)`

条件付き型は[テンプレートパラメータ](../templated_annotations.md)に依存しているため、テンプレートパラメータが定義されている関数でのみ使用できます。

## 応用例

PHPの数値加算のユーザーランド実装を作りたいと仮定しましょう（ただし、実際にはこれを行わないでください）。これを条件付き戻り値型で型付けできます：

```php
<?php
/** 
 * @template T of int|float
 * @param T $a
 * @param T $b
 * @return int|float
 * @psalm-return (T is int ? int : float)
 */
function add($a, $b) {
    return $a + $b;
}
```

`add($x, $y)` の結果を理解する際、Psalmはその特定の呼び出しについて `T` の値を推論しようとします。`add(1, 2)` を呼び出す場合、`T` は簡単に `int` と推論できます。次に、Psalmは提供された条件付き戻り値型
`(T is int ? int : float)`
を取り、既知の `T` の値 `int` を代入します。その結果、式は
`(int is int ? int : float)`
となり、これは `(true ? int : float)` に簡略化され、さらに `int` に簡略化されます。

`add(1, 2.1)` を呼び出す場合、`T` は代わりに `int|float` と推論されます。これは式 `(T is int ? int : float)` が次のように置き換えられることを意味します：
`(int|float is int ? int : float)`
ユニオン `int|float` は明らかに `int` ではないので、式は `(false ? int : float)` に簡略化され、さらに `float` に簡略化されます。

## ネストされた条件付き型

三項演算子と同様に、条件付き型をネストすることもできます：

```php
<?php
class A {
    const TYPE_STRING = 0;
    const TYPE_INT = 1;

    /**
     * @template T of int
     * @param T $i
     * @psalm-return (
     *     T is self::TYPE_STRING
     *     ? string
     *     : (T is self::TYPE_INT ? int : bool)
     * )
     */
    public static function getDifferentType(int $i) {
        if ($i === self::TYPE_STRING) {
            return "hello";
        }
        if ($i === self::TYPE_INT) {
            return 5;
        }
        return true;
    }
}
```

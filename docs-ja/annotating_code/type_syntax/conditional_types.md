# 条件付きタイプ

PsalmはTypeScriptの[conditional types](https://www.typescriptlang.org/docs/handbook/advanced-types.html#conditional-types) 。

条件型は以下のような形式を持つ：

`(<template param> is <union type> ? <union type> : <union type>)`

すべての条件型は括弧で囲む必要があります。`(...)`

条件型は[template parameters](../templated_annotations.md) に依存しているので、テンプレート・パラメータが定義されている関数の中でのみ使用することができます。

## アプリケーションの例

PHPの数値加算のユーザーランド実装を作りたいとしましょう(ただし、絶対にやらないでください)。条件付き戻り値型を使用して、このように入力することができます：

```php
<?php

/**  * @template T of int|float  * @param T $a  * @param T $b  * @return int|float  * @psalm-return (T is int ? int : float)  */ function add($a, $b) {     return $a + $b; }
```

`add($x, $y)` の結果を計算する際に、Psalm は`T` の値を推論しようとします。`add(1, 2)` を呼び出すとき、`T` は`int` として推論される。

`(T is int ? int : float)`

の既知の値`T` 、`int` を代入する。

`(int is int ? int : float)`

となり、`(true ? int : float)` 、`int` と単純化される。

`add(1, 2.1)` `(T is int ? int : float)` を呼び出すと、`T` は`int|float` と推論されることになる。

`(int|float is int ? int : float)`

結合`int|float` は明らかに`int` ではないので、式は`(false ? int : float)` に簡略化され、`float` に簡略化される。

## 入れ子の条件式

3項式と同じように、条件式を入れ子にすることもできる：

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

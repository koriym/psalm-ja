# オブジェクトの種類

## 無名オブジェクト

`object` は無名オブジェクト型の例です。この型もPHPでは有効な型です。

## 名前付きオブジェクト

`stdClass` `Foo` や`Bar\Baz` などが名前付きオブジェクト型の例です。これらの型もPHPで有効な型です。

## オブジェクトのプロパティ

Psalmは、オブジェクトのプロパティとその型を指定することができます：

```php
/** @param object{foo: string} $obj */
function takesObject(object $obj) : string {
    return $obj->foo;
}

takesObject((object) ["foo" => "hello"]);
```

オプションのプロパティは、末尾の`?` で示すことができます：

```php
/** @param object{optional?: string} */
```

## 一般的なオブジェクト型

Psalm は、次のような汎用オブジェクト型の使用をサポートしています。 `ArrayObject<int, string>`.どのようなジェネリックオブジェクトも、適切な[`@template` tags](../templated_annotations.md) でタイプヒンティングされるべきです。

## ジェネレータ

ジェネレータ型は最大4つのパラメータをサポートしています。 `Generator<int, string, mixed, void>`:

1.`TKey` `yield` キーのタイプ - デフォルト：`mixed` 2.`TValue` `yield` の値の型 - デフォルト:`mixed` 3.`TSend` `send()` メソッドのパラメータの型 - default:`mixed` 4.`TReturn` `getReturn()` メソッドの戻り値の型 - default：`mixed`

`Generator<int>`の省略形です。 `Generator<mixed, int, mixed, mixed>`.

`Generator<int, string>`は `Generator<int, string, mixed, mixed>`.

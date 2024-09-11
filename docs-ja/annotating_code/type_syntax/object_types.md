# オブジェクト型

## 名前のないオブジェクト

`object` は名前のないオブジェクト型の例です。この型はPHPでも有効な型です。

## 名前付きオブジェクト

`stdClass`、`Foo`、`Bar\Baz` などは名前付きオブジェクト型の例です。これらの型もPHPで有効な型です。

## オブジェクトプロパティ

Psalmはオブジェクトのプロパティとその期待される型を指定することをサポートしています。例：

```php
/** @param object{foo: string} $obj */
function takesObject(object $obj) : string {
    return $obj->foo;
}

takesObject((object) ["foo" => "hello"]);
```

オプショナルなプロパティは末尾に `?` を付けて示すことができます。例：

```php
/** @param object{optional?: string} */
```

## ジェネリックオブジェクト型

Psalmは `ArrayObject<int, string>` のようなジェネリックオブジェクト型の使用をサポートしています。ジェネリックオブジェクトは適切な [`@template` タグ](../templated_annotations.md) で型ヒントを付ける必要があります。

## ジェネレータ

ジェネレータ型は最大4つのパラメータをサポートします。例：`Generator<int, string, mixed, void>`：

1. `TKey`、`yield` キーの型 - デフォルト: `mixed`
2. `TValue`、`yield` 値の型 - デフォルト: `mixed`
3. `TSend`、`send()` メソッドのパラメータの型 - デフォルト: `mixed`
4. `TReturn`、`getReturn()` メソッドの戻り値の型 - デフォルト: `mixed`

`Generator<int>` は `Generator<mixed, int, mixed, mixed>` の省略形です。

`Generator<int, string>` は `Generator<int, string, mixed, mixed>` の省略形です。

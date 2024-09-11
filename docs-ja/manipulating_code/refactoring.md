# コードのリファクタリング

メソッドやクラスの移動など、コードベースに大きな変更を加えたいことがある。

Psalmにはリファクタリングツールがあり、`vendor/bin/psalm-refactor` または`vendor/bin/psalm --refactor` のどちらかでアクセスし、適切なコマンドを実行します。

## すべてのクラスをある名前空間から別の名前空間に移動する

```
vendor/bin/psalm-refactor --move "Ns1\*" --into "Some\Other\Namespace"
```

これは、`Ns1` (e.g.`Ns1\Foo`,`Ns1\Baz`) にあるすべてのクラスを指定された名前空間に移動します。ファイルは必要に応じて移動されます。

## ネームスペース間のクラスの移動

```
vendor/bin/psalm-refactor --move "Ns1\Foo" --into "Ns2"
```

これはPsalmにクラス`Ns1\Foo` を名前空間`Ns2` に移動するよう指示します。したがって、`Ns1\Foo` への参照はすべて`Ns2\Foo` になります。）ファイルは必要に応じて移動されます。

## クラスの移動と名前の変更

```
vendor/bin/psalm-refactor --rename "Ns1\Foo" --to "Ns2\Bar\Baz"
```

`Ns1\Foo` `Ns2\Bar\Baz`これはPsalmにクラス`Ns1\Foo` を名前空間`Ns2\Bar` に移動し、`Baz` にリネームすることを指示します。）ファイルは必要に応じて移動されます。

## クラス間のメソッドの移動

```
vendor/bin/psalm-refactor --move "Ns1\Foo::bar" --into "Ns2\Baz"
```

`Ns1\Foo::bar` `Ns2\Baz::bar`これは`Ns1\Foo` 内の`bar` というメソッドを`Ns2\Baz` というクラスに移動するよう Psalm に指示します。）Psalmは現在、静的メソッドを任意のクラス間に移動させたり、インスタンスメソッドをそのインスタンスの子クラスに移動させたりすることができます。

## メソッドの移動と名前の変更

```
vendor/bin/psalm-refactor --rename "Ns1\Foo::bar" --to "Ns2\Baz::bat"
```

`Ns1\Foo::bar` `Ns2\Baz::bat`これはPsalmにメソッド`Ns1\Foo::bar` をクラス`Ns2\Baz` に移動し、`bat` にリネームすることを指示します。）

# コードのリファクタリング

時にはメソッドやクラスを移動するなど、コードベースに大きな変更を加えたいことがあります。

Psalmにはリファクタリングツールがあり、`vendor/bin/psalm-refactor`または`vendor/bin/psalm --refactor`の後に適切なコマンドを付けてアクセスできます。

## あるネームスペースから別のネームスペースにすべてのクラスを移動する

```
vendor/bin/psalm-refactor --move "Ns1\*" --into "Some\Other\Namespace"
```

これにより、`Ns1`内のすべてのクラス（例：`Ns1\Foo`、`Ns1\Baz`）を指定されたネームスペースに移動します。必要に応じてファイルも移動されます。

### コードのリファクタリング

時にはメソッドやクラスを移動するなど、コードベースに大きな変更を加えたいことがあります。

Psalmにはリファクタリングツールがあり、`vendor/bin/psalm-refactor`または`vendor/bin/psalm --refactor`の後に適切なコマンドを付けてアクセスできます。

## あるネームスペースから別のネームスペースにすべてのクラスを移動する

```
vendor/bin/psalm-refactor --move "Ns1\*" --into "Some\Other\Namespace"
```

これにより、`Ns1`内のすべてのクラス（例：`Ns1\Foo`、`Ns1\Baz`）を指定されたネームスペースに移動します。必要に応じてファイルも移動されます。

## ネームスペース間でクラスを移動する

```
vendor/bin/psalm-refactor --move "Ns1\Foo" --into "Ns2"
```

これは、クラス`Ns1\Foo`をネームスペース`Ns2`に移動するようPsalmに指示します。そのため、`Ns1\Foo`への参照はすべて`Ns2\Foo`になります。必要に応じてファイルも移動されます。

## クラスの移動と名前変更

```
vendor/bin/psalm-refactor --rename "Ns1\Foo" --to "Ns2\Bar\Baz"
```

これは、クラス`Ns1\Foo`をネームスペース`Ns2\Bar`に移動し、名前を`Baz`に変更するようPsalmに指示します。そのため、`Ns1\Foo`への参照はすべて`Ns2\Bar\Baz`になります。必要に応じてファイルも移動されます。

## クラス間でメソッドを移動する

```
vendor/bin/psalm-refactor --move "Ns1\Foo::bar" --into "Ns2\Baz"
```

これは、`Ns1\Foo`内の`bar`という名前のメソッドをクラス`Ns2\Baz`に移動するようPsalmに指示します。そのため、`Ns1\Foo::bar`への参照はすべて`Ns2\Baz::bar`になります。現在のところ、Psalmは任意のクラス間で静的メソッドを移動することを許可し、インスタンスメソッドをそのインスタンスの子クラスに移動することを許可しています。

## メソッドの移動と名前変更

```
vendor/bin/psalm-refactor --rename "Ns1\Foo::bar" --to "Ns2\Baz::bat"
```

これは、メソッド`Ns1\Foo::bar`をクラス`Ns2\Baz`に移動し、名前を`bat`に変更するようPsalmに指示します。そのため、`Ns1\Foo::bar`への参照はすべて`Ns2\Baz::bat`になります。

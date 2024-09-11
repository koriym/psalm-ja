# 新しいissueタイプの追加

新しいイシュー・タイプを追加するには、以下に挙げるいくつかのステップが必要です。

## 新しいショートコードを生成する

`bin/max_used_shortcode.php` を実行し、表示された値に注目してください (`$max_shortcode`)

## 課題クラスを作成する

`Psalm\Issue` 名前空間に次のようなクラスを作成する：

```php
<?php

namespace Psalm\Issue;

final class MyNewIssue extends CodeIssue 
{
    public const ERROR_LEVEL = 2;
    public const SHORTCODE = 123;
}
```

`SHORTCODE` の値には`$max_shortcode + 1` を使用する。適切なエラー・レベルを選択するには、[Error levels](../running_psalm/error_levels.md) を参照してください。

拡張できる抽象クラスは多数あります：

*`CodeIssue` - 非特定のデフォルトissue。これはすべてのissueの基本クラスです。*`ClassIssue` - 特定のクラス（インターフェイス、trait、enumも）に関連する問題。これらの問題は、`referencedClass` 属性を使用することで、`psalm.xml` の特定のクラスに対して抑制することができます。 *`PropertyIssue` - 特定のプロパティに関連する問題。`psalm.xml` で`referencedProperty` を使用することで対象とすることができます *`FunctionIssue` - 特定の関数に関する問題。`referencedFunction` 属性で抑制可能。*`ArgumentIssue` - 特定の引数に関する問題。`referencedFunction` 。*`MethodIssue` - 特定のメソッドに関する問題。`referencedMethod` 。*`ClassConstantIssue` - 特定のクラス定数に関する問題です。`referencedConstant` でターゲットにできます。*`VariableIssue` - 特定の変数に関する問題。でターゲットにできます。`referencedVariable`

##`config.xsd` エントリを追加

`psalm.xml` を検証するために使用される`config.xsd` には、すべてのissueタイプを列挙する必要があります。適切な`type` 属性を選択してください。例:`PropertyIssue` を拡張するissueには`type="PropertyIssueHandlerType"` を使用します。

## 新しいissueのdocページを追加する

すべての課題はドキュメント化する必要があります。`docs/running_psalm/issues` フォルダにマークダウン・ファイルを作成してください。issueを説明するコードのスニペットを必ず含めてください。重要：スニペットはフェンスで囲まれたphpコードブロックを使用し、開始PHPタグ(`<?php`)を含める必要があります。スニペットは、あなたが文書化しようとしている問題を実際に生成する必要があります。私たちのテストスイートでチェックされます。

## docページへのリンクの追加

作成したdocページへのリンクを`docs/running_psalm/error_levels.md` と`docs/running_psalm/issues.md`

## ドキュメントテストを実行する

```
$ vendor/bin/phpunit tests/DocumentationTest.php
```

上記のステップをすべて（あるいは少なくともほとんど）実行したかどうかをチェックします。

## 新しいissueタイプをPsalmコアで使用してください。

```php
IssueBuffer::maybeAdd(new MyNewIssue(...))
```

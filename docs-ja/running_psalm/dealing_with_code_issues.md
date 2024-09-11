# コードの問題に対処する

Psalmには、多数の[code issues](issues.md) 。 各プロジェクトは、与えられた問題に対して、独自のレポートレベルを指定することができます。

Psalmのコード問題レベルは3つのカテゴリに分類されます：

-`error`<br> これはPsalmにメッセージを表示させ、最終的に0以外の終了ステータスで終了させます。

-`info`<br> これはPsalmにメッセージを表示させます。
  
-`suppress`<br> この場合、Psalmはコードの問題を完全に無視します。

特に大規模なコードベースにPsalmを導入する場合、3つ目の`suppress` が最も気になるところでしょう。

## 問題の抑制

issueを抑制するには、Psalmの設定と関数のdocblockの2つの方法があります。

### コンフィグによる抑制

設定ファイルの `<issueHandlers>`タグを使用することができます。

issueタイプによっては、`referencedMethod` 、`referencedClass` 、`referencedVariable` 、既知のトラブル・スポットを分離するために使用することができます。

```xml
<issueHandlers>
  <MissingPropertyType errorLevel="suppress" /> <!-- supress MissingPropertyType everywhere in your code -->

  <InvalidReturnType>
    <errorLevel type="suppress">
      <directory name="some_bad_directory" /> <!-- all InvalidReturnType issues in this directory are suppressed -->
      <file name="some_bad_file.php" />  <!-- all InvalidReturnType issues in this file are suppressed -->
    </errorLevel>
  </InvalidReturnType>
  <UndefinedMethod>
    <errorLevel type="suppress">
      <referencedMethod name="Bar\Bat::bar" /> <!-- not supported for all types of errors -->
      <file name="some_bad_file.php" />
    </errorLevel>
  </UndefinedMethod>
  <UndefinedClass>
    <errorLevel type="suppress">
      <referencedClass name="Bar\Bat\Baz" />
    </errorLevel>
  </UndefinedClass>
  <PropertyNotSetInConstructor>
    <errorLevel type="suppress">
        <referencedProperty name="Symfony\Component\Validator\ConstraintValidator::$context" />
    </errorLevel>
  </PropertyNotSetInConstructor>
  <UndefinedGlobalVariable>
    <errorLevel type="suppress">
      <referencedVariable name="$fooBar" /> <!-- if your variable is "$fooBar" -->
    </errorLevel>
  </UndefinedGlobalVariable>
  <PluginIssue name="IssueNameEmittedByPlugin" errorLevel="info" /> <!-- this is a special case to handle issues emitted by plugins -->
</issueHandlers>
```

### Docblockの抑制

関数のdocblockで`@psalm-suppress IssueName` 、Psalmの問題を抑制することもできます。

```php
<?php
/**
 * @psalm-suppress InvalidReturnType
 */
function (int $a) : string {
  return $a;
}
```

行レベルで問題を抑制することもできます。

```php
<?php
/**
 * @psalm-suppress InvalidReturnType
 */
function (int $a) : string {
  /**
   * @psalm-suppress InvalidReturnStatement
   */
  return $a;
}
```

すべてのissueを抑制したい場合は、複数の注釈の代わりに`@psalm-suppress all` 。

## ベースラインファイルの使用

エラーが大量にあり、それらを一度に修正したくない場合、Psalmは既存のコードのエラーを祖父にすることができ、同時に新しいコードに同じ種類のエラーがないようにすることができます。

```
vendor/bin/psalm --set-baseline
```

は、現在のエラーを含むファイル`psalm-baseline.xml` を生成します。あるいは、ベースライン・ファイルの名前を指定することもできる。

```
vendor/bin/psalm --set-baseline=your-baseline.xml
```

Psalmが他の場所（CIなど）で実行するときに使えるように、生成されたファイルをコミットする必要があります。そうすることで、Palmが他の場所（CIなど）で実行するときに、生成されたファイルを使うことができるようになります。

psalmを実行するときに生成されたベースラインを使うには2つのオプションがあります：

```
vendor/bin/psalm --use-baseline=your-baseline.xml
```

を使うか、設定を使うかです：

```xml
<?xml version="1.0"?>
<psalm
       ...
       errorBaseline="./path/to/your-baseline.xml"
>
   ...
</psalm>
```

ベースライン・ファイルを更新するには

```
vendor/bin/psalm --update-baseline
```

これで修正された問題は削除されますが、新しい問題は追加されません。新しいissueを追加するには、`--set-baseline=...`.

ベースラインなしでpsalmを実行したい場合は、以下を実行してください。

```
vendor/bin/psalm --ignore-baseline
```

ベースライン・ファイルは、コードベースを徐々に改善するのに最適な方法だ。

## プラグインを使う

特定のインターフェイスを実装しているクラスに対して特定の種類のエラーを抑制したい場合など、よりカスタムなものが必要な場合は、次のようなプラグインを使うことができます。`AfterClassLikeVisitInterface`

```php
<?php
namespace Foo\Bar;

use Psalm\Plugin\EventHandler\AfterClassLikeVisitInterface;
use Psalm\Plugin\EventHandler\Event\AfterClassLikeVisitEvent;
use ReflectionClass;

/**
 * Suppress issues dynamically based on interface implementation
 */
class DynamicallySuppressClassIssueBasedOnInterface implements AfterClassLikeVisitInterface
{
    public static function afterClassLikeVisit(AfterClassLikeVisitEvent $event)
    {
        $storage = $event->getStorage();
        if ($storage->user_defined
            && !$storage->is_interface
            && \class_exists($storage->name)
            && (new ReflectionClass($storage->name))->implementsInterface(\Your\Interface::class)
        ) {
            $storage->suppressed_issues[-1] = 'PropertyNotSetInConstructor';
        }
    }
}
```

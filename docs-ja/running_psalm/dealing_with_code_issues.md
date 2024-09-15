# コードの問題への対処

Psalmには多数の[コードの問題](issues.md)があります。各プロジェクトは、特定の問題に対して独自の報告レベルを指定できます。

Psalmのコードの問題レベルは3つのカテゴリに分類されます：
- `error`<br>
  これによりPsalmはメッセージを表示し、最終的に非ゼロの終了ステータスで終了します
- `info`<br>
  これによりPsalmはメッセージを表示します
- `suppress`<br>
  これによりPsalmはコードの問題を完全に無視します

3つ目のカテゴリである`suppress`は、特に大規模なコードベースにPsalmを導入する際に最も興味深いでしょう。

## 問題の抑制

問題を抑制する方法は2つあります - Psalm設定を介して、または関数のdocblockを介して。

### 設定による抑制

設定ファイルの`<issueHandlers>`タグを使用して、問題の扱い方に影響を与えることができます。
一部の問題タイプでは、`referencedMethod`、`referencedClass`、または`referencedVariable`を使用して、既知の問題箇所を特定することができます。

```xml
<issueHandlers>
  <MissingPropertyType errorLevel="suppress" /> <!-- コード全体でMissingPropertyTypeを抑制 -->
  <InvalidReturnType>
    <errorLevel type="suppress">
      <directory name="some_bad_directory" /> <!-- このディレクトリ内のすべてのInvalidReturnType問題を抑制 -->
      <file name="some_bad_file.php" />  <!-- このファイル内のすべてのInvalidReturnType問題を抑制 -->
    </errorLevel>
  </InvalidReturnType>
  <UndefinedMethod>
    <errorLevel type="suppress">
      <referencedMethod name="Bar\Bat::bar" /> <!-- すべての種類のエラーでサポートされているわけではありません -->
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
      <referencedVariable name="$fooBar" /> <!-- 変数が"$fooBar"の場合 -->
    </errorLevel>
  </UndefinedGlobalVariable>
  <PluginIssue name="IssueNameEmittedByPlugin" errorLevel="info" /> <!-- これはプラグインによって発行される問題を処理する特別なケースです -->
</issueHandlers>
```

### Docblockによる抑制

関数のdocblockで`@psalm-suppress IssueName`を使用して、Psalm問題を抑制することもできます。例：

```php
<?php
/** 
 * @psalm-suppress InvalidReturnType 
 */
function (int $a) : string {
  return $a;
}
```

行レベルで問題を抑制することもできます。例：

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

すべての問題を抑制したい場合は、複数のアノテーションの代わりに`@psalm-suppress all`を使用できます。

## ベースラインファイルの使用

多数のエラーがあり、それらをすべて一度に修正したくない場合、Psalmは既存のコードのエラーを許容しつつ、新しいコードで同じ種類のエラーが発生しないようにすることができます。

```
vendor/bin/psalm --set-baseline
```

を実行すると、現在のエラーを含む`psalm-baseline.xml`ファイルが生成されます。あるいは、ベースラインファイルの名前を指定することもできます。

```
vendor/bin/psalm --set-baseline=your-baseline.xml
```

Psalmが他の場所（例：CI）で実行されるときに使用できるように、生成されたファイルをコミットする必要があります。そうすれば、それらのエラーについて苦情を言うこともありません。

生成されたベースラインをPsalm実行時に使用するには2つのオプションがあります：

```
vendor/bin/psalm --use-baseline=your-baseline.xml
```

または設定を使用：

```xml
<?xml version="1.0"?>
<psalm
      ...
      errorBaseline="./path/to/your-baseline.xml">
   ...
</psalm>
```

そのベースラインファイルを更新するには、以下を使用します：

```
vendor/bin/psalm --update-baseline
```

これにより修正された問題は削除されますが、新しい問題は追加されません。新しい問題を追加するには、`--set-baseline=...`を使用します。

ベースラインなしでpsalmを実行したい場合は、次のように実行します：

```
vendor/bin/psalm --ignore-baseline
```

ベースラインファイルは、コードベースを徐々に改善するための優れた方法です。

## プラグインの使用

特定のインターフェースを実装するクラスで特定の種類のエラーを抑制するなど、より細かいカスタマイズが必要な場合は、`AfterClassLikeVisitInterface`を実装するプラグインを使用できます。

```php
<?php
namespace Foo\Bar;

use Psalm\Plugin\EventHandler\AfterClassLikeVisitInterface;
use Psalm\Plugin\EventHandler\Event\AfterClassLikeVisitEvent;
use ReflectionClass;

/**
 * インターフェースの実装に基づいて動的に問題を抑制する
 */
class DynamicallySuppressClassIssueBasedOnInterface implements AfterClassLikeVisitInterface
{
    public static function afterClassLikeVisit(AfterClassLikeVisitEvent $event)
    {
        $storage = $event->getStorage();
        if ($storage->user_defined
            && !$storage->is_interface
            && (new ReflectionClass($storage->name))->implementsInterface(SomeInterface::class)
        ) {
            $storage->suppressed_issues[] = 'MissingConstructor';
        }
    }
}
```

このプラグインは、`SomeInterface`を実装するすべてのクラスで`MissingConstructor`問題を抑制します。

プラグインの登録方法の詳細については、[プラグインの作成](plugins/authoring_plugins.md)を参照してください。

## まとめ

Psalmは、コードの問題を管理するための柔軟なオプションを提供しています：

1. 設定ファイルでの問題の抑制
2. Docblockでの問題の抑制
3. ベースラインファイルの使用
4. カスタムプラグインの作成

これらの方法を組み合わせることで、プロジェクトの特定のニーズに合わせてPsalmの動作をカスタマイズし、コード品質を段階的に改善することができます。

重要なのは、これらのツールを使って問題を隠すのではなく、コードの品質を向上させるための指針として使用することです。時間をかけて、抑制された問題を解決し、よりクリーンで型安全なコードベースを目指すことをお勧めします。

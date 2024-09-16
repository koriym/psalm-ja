# 非PHPファイルのチェック

Psalmは、`FileChecker`クラスを拡張することで、さまざまなPHPライクなファイルをチェックする機能をサポートしています。例えば、変数が他の場所で設定されているテンプレートがある場合、Psalmはそれらの変数をスクレイピングし、事前に設定された変数でテンプレートをチェックすることができます。

テンプレートチェッカーの例は[こちら](https://github.com/vimeo/psalm/blob/master/examples/TemplateChecker.php)で提供されています。

## `psalm.xml`の使用

カスタム`FileChecker`を使用するには、psalm.xmlのPsalm `fileExtensions`設定を更新する必要があります：

```xml
<fileExtensions>
    <extension name=".php" />
    <extension name=".phpt" checker="path/to/TemplateChecker.php" />
</fileExtensions>
```

## カスタムプラグインの使用

プラグインは、特定のファイル拡張子に対して独自のカスタムスキャナーとアナライザーの実装を登録できます。

```php
<?php
namespace Psalm\Example;

use Psalm\Plugin\PluginEntryPointInterface;
use Psalm\Plugin\PluginFileExtensionsInterface;
use Psalm\Plugin\FileExtensionsInterface;
use Psalm\Plugin\RegistrationInterface;

class CustomPlugin implements PluginEntryPointInterface, PluginFileExtensionsInterface
{
    public function __invoke(RegistrationInterface $registration, ?\SimpleXMLElement $config = null): void
    {
        // ... 通常のプラグインプロセス、スタブ登録、フック登録
    }

    public function processFileExtensions(FileExtensionsInterface $fileExtensions, ?SimpleXMLElement $config = null): void
    {
        $fileExtensions->addFileTypeScanner('phpt', TemplateScanner::class);
        $fileExtensions->addFileTypeAnalyzer('phpt', TemplateAnalyzer::class);
    }
}
```

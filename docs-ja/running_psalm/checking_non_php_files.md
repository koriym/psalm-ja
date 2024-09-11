# PHP以外のファイルのチェック

Psalm は`FileChecker` クラスを拡張することで、様々な PHP 系ファイルをチェックする機能をサポートしています。例えば、変数が他の場所に設定されているテンプレートがある場合、Psalmはそれらの変数をスクレイピングし、それらの変数があらかじめ設定されているテンプレートをチェックすることができます。

TemplateChecker のサンプルが[here](https://github.com/vimeo/psalm/blob/master/examples/TemplateChecker.php) にあります。

## 利用方法`psalm.xml`

カスタム`FileChecker` を確実に使用するには、psalm.xml の Psalm`fileExtensions` config を更新する必要があります：
```xml
<fileExtensions>
    <extension name=".php" />
    <extension name=".phpt" checker="path/to/TemplateChecker.php" />
</fileExtensions>
```

## カスタムプラグインを使う

プラグインは、特定のファイル拡張子に対する独自のカスタムスキャナーとアナライザーの実装を登録できます。

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
        // ... regular plugin processes, stub registration, hook registration
    }

    public function processFileExtensions(FileExtensionsInterface $fileExtensions, ?SimpleXMLElement $config = null): void
    {
        $fileExtensions->addFileTypeScanner('phpt', TemplateScanner::class);
        $fileExtensions->addFileTypeAnalyzer('phpt', TemplateAnalyzer::class);
    }    
}
```

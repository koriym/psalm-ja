# カスタム汚染ソース

アノテーションまたはプラグインを使用して、独自の汚染ソースを定義できます。

## 汚染ソースアノテーション

`@psalm-taint-source <taint-type>`アノテーションを使用して、ユーザー入力を提供する関数またはメソッドを示すことができます。

以下の例では、`input`汚染タイプが[Psalm\Type\TaintKindGroup](https://github.com/vimeo/psalm/blob/master/src/Psalm/Type/TaintKindGroup.php)で定義されている入力汚染の代替として指定されています。

```php
<?php
/**
 * @psalm-taint-source input
 */
function getQueryParam(string $name) : string {}
```

## カスタム汚染プラグイン

例えば、このプラグインはすべての`$bad_data`という名前の変数を汚染ソースとして扱います。

```php
<?php
namespace Some\Ns;

use PhpParser;
use Psalm\CodeLocation;
use Psalm\Context;
use Psalm\FileManipulation;
use Psalm\Plugin\EventHandler\AfterExpressionAnalysisInterface;
use Psalm\Plugin\EventHandler\Event\AfterExpressionAnalysisEvent;
use Psalm\Type\TaintKindGroup;

class BadSqlTainter implements AfterExpressionAnalysisInterface
{
    /**
     * 式がチェックされた後に呼び出されます
     *
     * @param  PhpParser\Node\Expr  $expr
     * @param  Context              $context
     * @param  FileManipulation[]   $file_replacements
     *
     * @return void
     */
    public static function afterExpressionAnalysis(AfterExpressionAnalysisEvent $event): ?bool {
        $expr = $event->getExpr();
        $statements_source = $event->getStatementsSource();
        $codebase = $event->getCodebase();

        if ($expr instanceof PhpParser\Node\Expr\Variable
            && $expr->name === 'bad_data'
        ) {
            $expr_type = $statements_source->getNodeTypeProvider()->getType($expr);

            // グローバルに一意のIDであるべきです
            // 行番号/開始オフセットを使用できます
            $expr_identifier = '$bad_data'
                . '-' . $statements_source->getFileName()
                . ':' . $expr->getAttribute('startFilePos');

            if ($expr_type) {
                $codebase->addTaintSource(
                    $expr_type,
                    $expr_identifier,
                    TaintKindGroup::ALL_INPUT,
                    new CodeLocation($statements_source, $expr)
                );
            }
        }

        return null;
    }
}
```

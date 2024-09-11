# 詩篇について

Psalmは静的解析ツールで、プログラムを掘り下げて型に関連するバグをできるだけ多く見つけようとする。

他の類似ツールよりもさらに進んだ機能がいくつかあります：

- **Mixed type warnings**<br /> Psalmが式の型を推論できない場合、`mixed` プレースホルダ型を使用します。`mixed` 型は時にバグを覆い隠すことがあるため、型を追跡することで、よくある落とし穴を回避することができます。

`if ($a && $a) {}` `if ($a && !$a) {}` - **Intelligent logic checks**<br /> Psalmはあなたのコードに対して行われた論理的なアサーションを追跡します。Psalmはまた、以前のコードパスで行われた論理的なアサーションを追跡し、`if ($a) {} elseif ($a) {}` のような問題を防ぎます。

- **Property initialisation checks**<br /> Psalm は、与えられたオブジェクトのすべてのプロパティが、コンストラクタが呼ばれた後に値を持つかどうかをチェックします。

- テイント解析**<br /> Psalmは、あなたのコードに[detect security vulnerabilities](https://psalm.dev/articles/detect-security-vulnerabilities-with-psalm) 。
  
- 言語サーバー**<br /> Psalmには言語サーバーがあり、[compatible with a range of different IDEs](https://psalm.dev/docs/running_psalm/language_server/) 。

- 自動修正**<br /> Psalmは[fix many of the issues it finds automatically](https://psalm.dev/docs/manipulating_code/fixing/) 。
  
- **Automatic refactoring**<br /> Psalmはコマンドラインから[perform simple refactors](https://psalm.dev/docs/manipulating_code/refactoring/) 。

## 出力例

与えられたファイル`implode_strings.php` ：

```php
<?php
$a = ['foo', 'bar'];
echo implode($a, ' ');
```

```bash
> ./vendor/bin/psalm implode_strings.php
ERROR: InvalidArgument - somefile.php:3:14 - Argument 1 of implode expects `string`, `array` provided (see https://psalm.dev/004)
```

#インスピレーション

詩篇のインスピレーションは主に2つある：

- Etsyの[Phan](https://github.com/etsy/phan) 、nikicの[php-ast](https://github.com/nikic/php-ast) 拡張機能を使用して抽象的な構文ツリーを作成する - Facebookの[Hack](http://hacklang.org/) 、PHPライクな言語で、ネイティブで多くの高度な型付け機能をサポートしているため、docblockは必要ない。

## インデックス

- 実行中の詩篇：     -[Installation](running_psalm/installation.md) -[Configuration](running_psalm/configuration.md) - プラグイン -[Using plugins](running_psalm/plugins/using_plugins.md) -[Authoring plugins](running_psalm/plugins/authoring_plugins.md) -[How Psalm represents types](running_psalm/plugins/plugins_type_system.md) -[Command line usage](running_psalm/command_line_usage.md) -[IDE support](running_psalm/language_server.md) - エラー処理：         -[Dealing with code issues](running_psalm/dealing_with_code_issues.md) -[Issue Types](running_psalm/issues.md) -[Checking non-PHP files](running_psalm/checking_non_php_files.md) - コードの注釈：     -[Typing in Psalm](annotating_code/typing_in_psalm.md) -[Supported Annotations](annotating_code/supported_annotations.md) -[Template Annotations](annotating_code/templated_annotations.md) - コードの操作：     -[Fixing code](manipulating_code/fixing.md) -[Refactoring code](manipulating_code/refactoring.md)


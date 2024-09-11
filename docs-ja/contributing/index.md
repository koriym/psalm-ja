# psalmへの貢献

Psalmは、[hundreds of developers](https://github.com/vimeo/psalm/graphs/contributors) 。

あなたがその一人になれることを願っています。

## スタート

[Here’s a rough guide to the codebase](how_psalm_works.md).

[Here's the philosophy underpinning the Psalm’s development](philosophy.md).

[a list of Psalm’s complexities](what_makes_psalm_complicated.md) もまとめた。

低空飛行の果実をお探しですか？[GitHub issues](https://github.com/vimeo/psalm/issues?q=is%3Aissue+is%3Aopen+label%3A%22easy+problems%22) 、解決はそれほど難しくないはずだ。

### 怖がらないで！

Psalm で作業することのすばらしい点は、Psalm のコードベースで何らかの型エラーを引き起こすことが非常に難しいということです。5,000近いPHPUnitテストがあるので、(CIシステムに気づかれずに)失敗するリスクは非常に小さいです。

### 静的解析がクールな理由

日々の PHP プログラミングでは具体的な問題を解決することになりますが、 その問題がそれほど複雑であることはほとんどありません。一方、Psalm は非常に難しい問題を解決しようとします。そのため、PHP コードを実際に実行することなく、PHP コードのバグを大量に検出することができます。

Psalmが行うことの背後には、興味深い理論もたくさんある。Psalmを改良するために本当にどんな理論も知っている必要はありませんが、あなたが望むなら、あなたは非常に深く行くことができます。

最後に、静的解析ツールの改善に取り組むことは、あなたをより良いPHP開発者にすることでもあります。

### ガイド

*[Editing callmaps](editing_callmaps.md) *[Adding a new issue type](adding_issues.md)

## プルリクエスト

プルリクエストを送る前に、以下のガイドラインに従っていることを確認してください：

ローカルで統合チェックを実行する：`composer tests`

新機能を追加したりバグを修正したりする場合は、忘れずにテストを追加しましょう！

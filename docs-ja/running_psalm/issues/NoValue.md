# ノーバリュー

Psalmが指定された式で使用可能なすべての型を無効にした場合に発行されます。これは、Psalmがデッドコードを見つけたか、文書化された型が網羅的でなかったことを示すことがよくあります。

php
&lt;?php

/**
 * 決して返さない
 */
関数 foo() : void {
    exit()；
}

$a = foo(); // Psalmは、foo()が何も返さないので、$aに型が含まれないことを知っています。
を返さないからです。

&lt;ignore&gt;``php
&lt;?php

関数 foo() : void {
    return throw new Exception(''); //Psalmはreturn式が使われないことを検出しました。
}
を返します。

&lt;ignore&gt;``php
&lt;?php
function shutdown(): never {die('アプリケーションが予期せず終了しました');}.
関数 foo(string $_a): void{}

foo(shutdown()); // foo()が呼び出されることはありません。
を呼び出すことはありません。

&lt;ignore&gt;``php
&lt;?php
$a = [];
/** @psalm-suppress TypeDoesNotContainType */
assert(!empty($a))；

count($a); // 上記のアサートは常に失敗します。ここで $a が持ちうる型はありません。
を返します。
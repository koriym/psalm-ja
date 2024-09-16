# 呼び出し可能型

Psalmは `callable` のための特別な形式をサポートしています。これは `Closure` にも注釈を付けるのに使用できます。

```
callable(Type1, OptionalType2=, SpreadType3...):ReturnType
```

型の後に `=` を追加すると、それがオプショナルであることを意味し、`...` を末尾に付けると、スプレッド演算子の使用を意味します。

この注釈を使用すると、特定の関数が `Closure` を返すことを指定できます。例：

```php
<?php
/** 
 * @return Closure(bool):int
 */
function delayedAdd(int $x, int $y) : Closure {
  return function(bool $debug) use ($x, $y) {
    if ($debug) echo "got here" . PHP_EOL;
    return $x + $y;
  };
}

$adder = delayedAdd(3, 4);
echo $adder(true);
```

## 純粋な呼び出し可能型

`callable` が純粋または不変である必要がある状況のために、`pure-callable` と `pure-Closure` のサブタイプも利用可能です。

これは、`callable` が `@psalm-pure` または `@psalm-mutation-free` でマークされた関数で使用される場合に便利です。例：

```php
<?php
/** @psalm-immutable */
class intList {
    /** @param list<int> $items */
    public function __construct(private array $items) {}
    
    /**
     * @param pure-callable(int, int): int $callback
     * @psalm-mutation-free
     */
    public function walk(callable $callback): int {
        return array_reduce($this->items, $callback, 0);
    }
}

$list = new intList([1,2,3]);

// これはOKです。呼び出し可能型が純粋であるため
echo $list->walk(fn (int $c, int $v): int => $c + $v);

// これは InvalidArgument エラーを引き起こします。クロージャが不純な関数を呼び出すため
echo $list->walk(fn (int $c, int $v): int => $c + random_int(1, $v));
```

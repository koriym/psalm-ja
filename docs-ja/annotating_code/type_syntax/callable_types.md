#呼び出し可能なタイプ

Psalmは、`callable`の特別な書式をサポートしている。また、`Closure` の注釈にも使用できる。

```
callable(Type1, OptionalType2=, SpreadType3...):ReturnType
```

型の後に`=` をつけると省略可能であることを意味し、`...` をつけるとスプレッド演算子の使用を意味する。

このアノテーションを使用すると、指定した関数が`Closure` を返すように指定できます。

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

## 純粋な callables

`callable` が純粋または不変である必要がある場合、`pure-callable` と`pure-Closure` というサブタイプも利用できる。

これは、例えば`@psalm-pure` や`@psalm-mutation-free` でマークされた関数の中で`callable` を使用する場合に便利です：

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

// This is ok, as the callable is pure
echo $list->walk(fn (int $c, int $v): int => $c + $v);

// This will cause an InvalidArgument error, as the closure calls an impure function
echo $list->walk(fn (int $c, int $v): int => $c + random_int(1, $v));
```

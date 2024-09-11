#リスキー・トゥルシー・ファルシーの比較

少なくとも1つの型が真理か偽りのみであり、他の型が真理と偽りの両方の値を含む可能性がある場合に、複数の型で値を比較するときに発行されます。

```php
<?php

/**
 * @param array|null $arg
 * @return void
 */
function foo($arg) {
    if ($arg) {
        // this is risky, bc the empty array and null case are handled together
    }
    
    if (!$arg) {
        // this is risky, bc the empty array and null case are handled together  
    }
}
```

## なぜこれが悪いのか

変数のtruthy/falsy型はしばしば忘れられがちで、明示的に扱われないため、エラーの追跡が困難になる。

## 修正方法

厳密な比較で明示的に変数を検証する。

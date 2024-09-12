# UnusedIssueHandlerSuppression
設定ファイル内のissue型抑制が問題を抑制するために使用されていない場合に発生します。
[findUnusedIssueHandlerSuppression](../configuration.md#findunusedissuehandlersuppression)で有効化されます。

```php
<?php
$a = 'Hello, World!';
echo $a;
```

```xml
<?xml version="1.0" encoding="UTF-8"?>
<issueHandlers>
    <PossiblyNullOperand errorLevel="suppress"/>
</issueHandlers>
```

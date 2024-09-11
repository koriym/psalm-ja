#未使用IssueHandlerの抑制

設定ファイルのissueタイプ抑制がissueの抑制に使用されていない場合に発行されます。

[findUnusedIssueHandlerSuppression](../configuration.md#findunusedissuehandlersuppression) 。 

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

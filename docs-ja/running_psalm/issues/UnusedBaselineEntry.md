# UnusedBaselineEntry

ベースラインエントリがissueの抑制に使用されていない場合に発行されます。

[findUnusedBaselineEntry](../configuration.md#findunusedbaselineentry) 。 

```php
<?php
$a = 'Hello, World!';
echo $a;
```
```xml
<?xml version="1.0" encoding="UTF-8"?>
<files>
    <file src="example.php">
        <UnusedVariable>
            <!-- The following entry is unused and should be removed. -->
            <code>$a</code>
        </UnusedVariable>
    </file>
</files>
```

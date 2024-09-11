# InvalidTypeImport

`@psalm-import-type` でインポートされた型が無効な場合に発行されます。

```php
<?php

namespace A;

class Types {}

namespace B;
use A\Types;
/** @psalm-import-type UnknownType from Types */
class C {}
```

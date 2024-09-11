# TaintedSql

ユーザ制御の入力をSQLコマンドに渡すことができる場合に発せられる。

```php
<?php

class A {
    public function deleteUser(PDO $pdo) : void {
        $userId = self::getUserId();
        $pdo->exec("delete from users where user_id = " . $userId);
    }

    public static function getUserId() : string {
        return (string) $_GET["user_id"];
    }
}
```

# TaintedUserSecret
汚染入力検出がオンになっていて、ユーザーの秘密としてマークされたデータがあるべきでない場所で検出された場合に発生します。

```php
<?php
class User {
    /**
     * @psalm-taint-source user_secret
     */
    public function getPassword() : string {
        return "$omePa$$word";
    }
}

function showUserPassword(User $user) {
    echo $user->getPassword();
}
```

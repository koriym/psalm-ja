# TaintedUserSecret

汚染された入力の検出がオンになっており、ユーザー・シークレットとしてマークされたデータが、あるべきでない場所で検出された場合に発せられる。

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

# Psalmの言語サーバーの使用

Psalmには言語サーバー互換性サポートが組み込まれており、お気に入りのIDEで実行できるようになりました。
現在、診断（エラーと警告の検出）、定義へのジャンプ、ホバーをサポートしており、オートコンプリートも限定的にサポートしています（PRを歓迎します！）。
様々なエディタで良好に動作します（アルファベット順）：

## クライアント設定

### Emacs

[eglot](https://github.com/joaotavora/eglot)で動作確認しました。
以下は使用した設定です：

```
(when (file-exists-p "vendor/bin/psalm-language-server")
  (progn
    (require 'php-mode)
    (require 'eglot)
    (add-to-list 'eglot-server-programs '(php-mode . ("php" "vendor/bin/psalm-language-server")))
    (add-hook 'php-mode-hook 'eglot-ensure)
    (advice-add 'eglot-eldoc-function :around
                (lambda (oldfun)
                  (let ((help (help-at-pt-kbd-string)))
                    (if help (message "%s" help) (funcall oldfun)))))
    )
  )
```

### PhpStorm

#### ネイティブサポート

PhpStorm 2020.3以降、psalmのサポートがデフォルトで有効になっています。詳細は[こちら](https://www.jetbrains.com/help/phpstorm/using-psalm.html)で確認できます。

#### LSPを使用する場合

alternatively、psalmは`gtache/intellij-lsp`プラグイン（[Jetbrains承認版](https://plugins.jetbrains.com/plugin/10209-lsp-support)、[最新版](https://github.com/gtache/intellij-lsp/releases/tag/v1.6.0)）で動作します。セットアップはGUIで行います。

プラグインをインストールすると、"Languages & Frameworks"タブの下に"Language Server Protocol"セクションが表示されるはずです。
"Server definitions"タブでPsalmの定義を追加する必要があります：
- `Executable`を選択
- 拡張子：`php`
- パス：`<PHPバイナリへのパス>` 例：`/usr/local/bin/php`または`C:\php\php.exe`
    - これは絶対パスである必要があり、単に`php`ではいけません
- 引数：`vendor/bin/psalm-language-server`（Windowsの場合は`vendor/vimeo/psalm/psalm-language-server`、または'グローバル'インストールの場合は'%APPDATA%' + `\Composer\vendor\vimeo\psalm\psalm-language-server`、'%APPDATA%'環境変数は通常`C:\Users\<homedir>\AppData\Roaming\`のようになります）

"Timeouts"タブで初期化タイムアウトを調整できます。これは大規模なプロジェクトがある場合に重要です。"Init"の値を、Psalmがプロジェクト全体とその依存関係をスキャンすることを許可するミリ秒数に設定する必要があります。大規模なPHPフレームワークを使用するいくつかのプロジェクトを開く場合、高性能のビジネスラップトップで、Initに`240000`ミリ秒を試してみてください。

### Sublime Text

優れたSublimeの[LSPプラグイン](https://github.com/tomv564/LSP)を以下の設定で使用しています（Package Settings > LSP > Settings）：

```json
    "clients": {
        "psalm": {
            "command": ["php", "vendor/bin/psalm-language-server"],
            "selector": "source.php | embedding.php",
            "enabled": true
        }
    }
```

### Vim & Neovim

**ALE**

[ALE](https://github.com/w0rp/ale)はPsalmをサポートしています（v2.3.0以降）。

```vim
let g:ale_linters = { 'php': ['php', 'psalm'] }
```

**vim-lsp**

[vim-lsp](https://github.com/prabirshrestha/vim-lsp)でも動作確認しました。
以下は使用した設定です（Vim用）：

```vim
au User lsp_setup call lsp#register_server({
     \ 'name': 'psalm-language-server',
     \ 'cmd': {server_info->[expand('vendor/bin/psalm-language-server')]},
     \ 'allowlist': ['php'],
     \ })
```

**coc.nvim**

[coc.nvim](https://github.com/neoclide/coc.nvim)でも動作します。
`coc-settings.json`に以下の設定を追加してください：

```jsonc
  "languageserver": {
    "psalmls": {
      "command": "vendor/bin/psalm-language-server",
      "filetypes": ["php"],
      "rootPatterns": ["psalm.xml", "psalm.xml.dist"],
      "requireRootPattern": true
    }
  }
```

### VS Code

[Psalmプラグインはこちらから入手できます](https://marketplace.visualstudio.com/items?itemName=getpsalm.psalm-vscode-plugin)（VS Code 1.26以降が必要）：

## Dockerコンテナでサーバーを実行する

`--map-folder`オプションを必ず使用してください。引数なしで使用すると、サーバーのCWDをホストのプロジェクトルートフォルダにマッピングします。カスタムマッピングを指定することもできます。例：

```bash
docker-compose exec php /usr/share/php/psalm/psalm-language-server \
   -r=/var/www/html \
   --map-folder=/var/www/html:$PWD
```

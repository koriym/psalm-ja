# Psalmの言語サーバーを使う

PsalmはLanguage Server Compatibilityをサポートするようになりました。

現在、診断（エラーや警告の検出）、go-to-definition、hoverをサポートしています。

様々なエディタ（アルファベット順）で動作します：

## クライアント設定

### Emacs

で動作するようになりました。[eglot](https://github.com/joaotavora/eglot)

これは私が使った設定です：

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

PhpStorm 2020.3では、psalmがデフォルトでサポートされています。[here](https://www.jetbrains.com/help/phpstorm/using-psalm.html)

#### LSPと

psalm は`gtache/intellij-lsp` プラグイン ([Jetbrains-approved version](https://plugins.jetbrains.com/plugin/10209-lsp-support),[latest version](https://github.com/gtache/intellij-lsp/releases/tag/v1.6.0)) でも動作します。

セットアップはGUIで行う。

プラグインをインストールすると、"Languages &amp; Frameworks "タブの下に "Language Server Protocol "セクションが表示されます。

Server definitions "タブでPsalmの定義を追加してください：

 -`Executable` - Extension:`php` - Path： `<path-to-php-binary>`例:`/usr/local/bin/php` または`C:\php\php.exe` - これは絶対パスであるべきで、単に`php` - 引数:`vendor/bin/psalm-language-server` (Windowsでは`vendor/vimeo/psalm/psalm-language-server` を使用するか、'グローバル'インストールには '%APPDATA%' +`\Composer\vendor\vimeo\psalm\psalm-language-server` を使用します。'%APPDATA%' 環境変数はおそらく次のようなものです。 `C:\Users\<homedir>\AppData\Roaming\`)

Timeouts "タブでは、初期化のタイムアウトを調整できます。これは大規模なプロジェクトでは重要です。Init "の値は、Psalmがプロジェクト全体とプロジェクトの依存関係をスキャンするのに必要なミリ秒数に設定してください。大規模なPHPフレームワークを使用するいくつかのプロジェクトを、ハイエンドのビジネス用ラップトップで開く場合は、Initに`240000` ミリ秒を試してみてください。

### サブライムテキスト

私は優れたSublime[LSP plugin](https://github.com/tomv564/LSP) を以下の設定(Package Settings &gt; LSP &gt; Settings)で使用しています：
```json
    "clients": {
        "psalm": {
            "command": ["php", "vendor/bin/psalm-language-server"],
            "selector": "source.php | embedding.php",
            "enabled": true
        }
    }
```

### Vim &amp; Neovim

**ALE**

[ALE](https://github.com/w0rp/ale) はPsalmをサポートしています(v2.3.0以降)。

```vim
let g:ale_linters = { 'php': ['php', 'psalm'] }
```

**vim-lsp**

で動作するようになりました。[vim-lsp](https://github.com/prabirshrestha/vim-lsp)

これは私が使った設定です（Vim用）：

```vim
au User lsp_setup call lsp#register_server({
     \ 'name': 'psalm-language-server',
     \ 'cmd': {server_info->[expand('vendor/bin/psalm-language-server')]},
     \ 'allowlist': ['php'],
     \ })
```

**coc.nvim**

また、[coc.nvim](https://github.com/neoclide/coc.nvim) 。

`coc-settings.json` に設定を追加する：

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

### VSコード

[Get the Psalm plugin here](https://marketplace.visualstudio.com/items?itemName=getpsalm.psalm-vscode-plugin) (VS Code 1.26+ が必要です)：

## dockerコンテナでサーバーを動かす

必ず`--map-folder` オプションを使ってください。引数なしで使用すると、サーバーのCWDがホストのプロジェクトルートフォルダにマッピングされます。カスタムマッピングを指定することもできます。例えば
```bash
docker-compose exec php /usr/share/php/psalm/psalm-language-server \
   -r=/var/www/html \
   --map-folder=/var/www/html:$PWD
```

### プロンプト

#### 要件

- 現在のテーマ/fukasawa/をco-op.oshinco.comに移行する
  /Users/ackey/Documents/Share/github.com/ekkun/co-op.oshinco.com/Local/app/public/admin/wp-content/themes/co-op.oshinco.com
  /co-op.oshinco.com/のディレクトリ構造を活かす
  PHP, CSS, JSをコピーする
- 投稿（post）をCPTの実績（case）に移行する
  投稿（post）は非表示にする（または削除）

- CPTニュース（news）を新規作成する
  /fukasawa/のテンプレートにCPTニュースの枠がないため新規作成する
- WordPressデフォルトギャラリーブロックはSplideを使用する

- 今後/assets/はViteで管理する
  - /assets/src/scss/style.scss に必要なファイルを全てインポートする
    /assets/css/style.css に出力する
  - /assets/src/js/main.js に必要なファイルを全てインポートする
    /assets/js/main.js に出力する
  - /assets/fonts/ にフォントを配置する
  - /assets/images/ に画像を配置する

#### デザイン

- レトロフューチャーな雰囲気
- 南インド（ポンディシェリ）のような「古い部分」と「新しい部分」が混在するような見た目
- ベースは/fukasawa/の見た目を参照する（fukasawa関連のファイルは残さないこと）
- Masonry Cascading grid layoutのようなグリッドレイアウトにする（参照https://masonry.desandro.com/）
- カラーは以下の通り
  0: #444
  1: #009ddd
  2: #ff9f40
  3: #767676
  4: #fff
- ベースはtailwind cssを使用する
- 次節でionic frameworkを導入し、tailwind cssと併用する
- アイコンはhttps://ionic.io/ionicons/v4を使用する
- フォントはGen Interface JP（https://gen.typesetting.jp/）で作成
- サブフォントはNoto Serif JPを使用する
- GSAPのような動きも◎

- まずFigmaのデザインカンプを作成する

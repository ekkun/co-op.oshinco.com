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

- iPhone Duo（https://www.apple.com/jp/iphone-duo/）の繊細さのある「ミニマルデザイン」のページレイアウトと色彩をベースにする
  （幾何学形状、フラットカラー、細い線、広い余白で抽象化し、高級ミニマルデザイン）
- 建築ポスターや国際的なデザイン事務所のような、静謐で現代的な表現にしてください。
- 補足として、レトロフューチャーな雰囲気（南インド（ポンディシェリ）のような「古い部分」と「新しい部分」が混在するような見た目）
- 素材は/Users/ackey/Documents/Share/github.com/ekkun/co-op.oshinco.com/Local/app/public/admin/wp-content/themes/co-op.oshinco.com/assets/materials/を参照
  svg化するなどして使用する
- ベースは/fukasawa/の見た目を参照する（fukasawa関連のファイルは残さないこと）
- Masonry Cascading grid layoutのようなグリッドレイアウトにする（参照https://masonry.desandro.com/）
- カラーは以下の通り
  0: #444
  1: #009ddd
  2: #ff9f40
  3: #767676
  4: #fff
  ※ベースを基とした色の追加は可能
- 左カラムは固定（Sticky）で表示する
- ベースはionic frameworkを導入し、次節でtailwind cssを使用する
- アイコンはhttps://ionic.io/ionicons/v4 を使用する
- フォントはGen Interface JP（https://gen.typesetting.jp/）で作成
- サブフォントはNoto Serif JPを使用する
- メディアクエリーよりコンテナークエリーを優先する
- GSAPのような動きも◎
- ページネーションはスクロールで無限に読み込む方式（ion-infinite-scroll）にする（https://ionicframework.com/docs/ja/api/infinite-scroll）
- SVGまわりはmaskで色を変えられるようにする（ホバー時など）

- まずFigmaのデザインカンプを作成する
  https://www.figma.com/design/9zsc0TWEI0Vne2n7YSWTdX/co-op.oshinco.com?node-id=0-1&p=f&t=BuSwmoO2dVZcqnba-0

### コーディング

- scssは現在ある/Users/ackey/Documents/Share/github.com/ekkun/co-op.oshinco.com/Local/app/public/admin/wp-content/themes/co-op.oshinco.com/assets/src/scssを参照する
  - 今後/assets/はViteで管理する
    - /assets/src/scss/style.scss に必要なファイルを全てインポートする
      /assets/css/style.css に出力する
      不要なscssは削除して良いです！
    - /assets/src/js/main.js に必要なファイルを全てインポートする
      /assets/js/main.js に出力する
    - /assets/fonts/ にフォントを配置する
    - /assets/images/ に画像を配置する
- 素材は/Users/ackey/Documents/Share/github.com/ekkun/co-op.oshinco.com/Local/app/public/admin/wp-content/themes/co-op.oshinco.com/assets/materials/を参照
  svg化するなどして使用する
- ベースは/fukasawa/の見た目を参照する（fukasawa関連のファイルは残さないこと）
- Masonry Cascading grid layoutのようなグリッドレイアウトにする（参照https://masonry.desandro.com/）
- カラーは以下の通り
  0: #444
  1: #009ddd
  2: #ff9f40
  3: #767676
  4: #fff
  ※ベースを基とした色の追加は可能
- 左カラムは固定（Sticky）で表示する
- ベースはionic frameworkを導入し、次節でtailwind cssを使用する
- アイコンはhttps://ionic.io/ionicons/v4 を使用する
- フォントはGen Interface JP（https://gen.typesetting.jp/）で作成
- サブフォントはNoto Serif JPを使用する
- メディアクエリーよりコンテナークエリーを優先する
- GSAPのような動きも◎
- ページネーションはスクロールで無限に読み込む方式（ion-infinite-scroll）にする（https://ionicframework.com/docs/ja/api/infinite-scroll）
- SVGまわりはmaskで色を変えられるようにする（ホバー時など）

- 標準ブロックのスタイルをベースデザインに合うようにリデザインする

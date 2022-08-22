## cloneしたとき
docker-compose up -d
.env修正
fpmコンテナ入る
php artisan migrate
npm install (多分いる)
npm run dev (多分いる)


## 1から造ったときにやったこと
1. docker環境を構築する
    $ docker-compose up -d
2. php-fpm コンテナへ入る
3. プロジェクト作成
    composer create-project laravel/laravel ebiten
4. src/ebiten 以下のファイルをすべて src 配下へ移動させて、ebitenフォルダを削除
5. .envを開き、データベース関連を以下のように修正
    ~~~
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=ebiten
DB_USERNAME=user
DB_PASSWORD=password
    ~~~
6. マイグレーション実施（コンテナ内にて）
    php artisan migrate
7. 一旦git 追加してfirst commit しておく（コンテナ外にて）
    $ git init
    $ touch .gitignore
    追加したgitignoreに mysql を一文を追加（mysqlフォルダをgitに載せない）
8. laravel ui 導入
    composer require laravel/ui
    php artisan ui bootstrap --auth
    npm install && npm run dev
    エラーがここで出るので、一回これ実行
    npm install resolve-url-loader@^5.0.0 --save-dev --legacy-peer-deps
    npm run dev


## mime typeについて
wasmファイルのレスポンスをapplication/wasm にするために、nginxで
レスポンスが変わるように設定ファイルを追加してある


# web-app-tutorial-laravel-template

## 概要
記事、記事へのコメントのCRUD処理（パスワード認証が必要な機能あり）

## 使用言語、ライブラリ
![PHP](https://img.shields.io/badge/PHP-8.2.29-777BB4?logo=php&logoColor=white)
![Docker Compose](https://img.shields.io/badge/Docker%20Compose-2.x-2496ED?logo=docker&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?logo=laravel&logoColor=white)
![Laravel Sanctum](https://img.shields.io/badge/Laravel%20Sanctum-Available-FF2D20?logo=laravel&logoColor=white)
![SQLite](https://img.shields.io/badge/SQLite-3.x-003B57?logo=sqlite&logoColor=white)
![Swagger](https://img.shields.io/badge/Swagger-OpenAPI%203.1-85EA2D?logo=swagger&logoColor=black)

## 環境構築
```
$ cd web-app-tutorial-laravel
$ make build
$ make app

# コンテナ内での作業
$ php artisan serve --host 0.0.0.0 --port 8000 #サーバ起動
$ php artisan key:generate
$ chmod -R 775 storage bootstrap/cache
$ php artisan migrate
$ php artisan db:seed 
```

## APIアクセス（Swagger UI）
http://localhost:8000/api/documentation でL5 Swagger UI画面にアクセス  
<img src='README_images/l5-swagger-ui.png' width='350px'>  
  
### テスト用アカウント  
email: taro@example.com  
password: password  
<br>
記事、コメントの一覧や詳細の取得は未ログインでもアクセス可能だが、  
記事、コメントの作成、編集、削除にはログインが必要のため  
先にログインを済ませておく。

#### ログイン手順：
①Auth配下の **GET /sanctum/csrf-cookie** のタブを開く
<img src='README_images/get-csrf-cookie.png' width='350px'>  

②Excecuteを押下し　**GET /sanctum/csrf-cookie**　を実行する  
<img src='README_images/get-csrf-cookie-execute.png' width='350px'> 

③204レスポンスが返ることを確認する  
<img src='README_images/get-csrf-cookie-done.png' width='350px'> 

④検証ツールのApplicationタブを開き、URL decodeされたXSRF-TOKENを取得する  
<img src='README_images/get-URL-decoded-cookie.png' width='350px'>  

⑤画面上部のAuthorizeボタンを押下する  
<img src='README_images/click-authorize.png' width='350px'>  

⑥ ④で取得したXSRF-TOKENを設定して閉じる  
<img src='README_images/authorize.png' width='350px'>  

<img src='README_images/authorize-close.png' width='350px'>  

⑦Auth配下の **POST /login** を実行する
<img src='README_images/login-execute.png' width='350px'>  

⑧200レスポンスが返ることを確認する   
<img src='README_images/login-response.png' width='350px'>  

⑨ ④~⑥と同様URL decodeされたXSRF-TOKENを取得し、Authorizeに設定する  
<img src='README_images/get-cookie-after-login.png' width='350px'>  

⑩ログインが必要な **POST /articles**(記事作成) 等も実行可能 
<img src='README_images/create-article-after-login.png' width='350px'> 

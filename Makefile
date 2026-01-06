# 変数定義
APP_SERVER := laravel-app-server

# docker-compose基本コマンド
build:
	docker compose build
up:
	docker compose up -d
stop:
	docker compose stop
start:
	docker compose start
down:
	docker compose down --remove-orphans
down-v:
	docker compose down --remove-orphans -v
destroy:
	docker compose down --remove-orphans -v --rmi all
restart:
	@make down
	@make up

# コンテナログイン
app:
	docker compose exec $(APP_SERVER) sh

# Laravel開発サーバ起動
serve:
	docker compose exec -d $(APP_SERVER) php artisan serve --host 0.0.0.0 --port 8000
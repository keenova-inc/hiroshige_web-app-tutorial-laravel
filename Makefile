# 変数定義
SRC_DIR := src
APP_SERVER := laravel-app-server
WEB_SERVER := laravel-web-server
DB_SERVER  := laravel-db-server

# Makefileで定義する独自コマンド
.PHONY: setup build up stop start down down-v destroy restart app web db

# Laravelプロジェクトの新規作成
setup:
	@if [ ! -d $(SRC_DIR)/vendor ]; then \
		mkdir $(SRC_DIR); \
		make up; \
		docker compose exec $(APP_SERVER) composer create-project --prefer-dist "laravel/laravel=12.*" .; \
		docker compose cp ./docker-config/php/.env.tutorial $(APP_SERVER):/var/www/html/.env; \
		docker compose exec $(APP_SERVER) php artisan key:generate; \
		docker compose exec $(APP_SERVER) chmod -R 777 storage bootstrap/cache; \
		echo "\n============================="; \
		echo "🚀 URL:http://localhost:8080"; \
		echo "============================="; \
	else \
		echo "-> Laravelプロジェクトが存在するため、インストールをスキップしました。"; \
	fi

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
web:
	docker compose exec $(WEB_SERVER) sh
db:
	docker compose exec $(DB_SERVER) bash

hello:
	@echo "hi"
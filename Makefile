# 変数定義
SRC_DIR := src
APP_SERVER := laravel-app-server

# Makefileで定義する独自コマンド
.PHONY: setup build up stop start down down-v destroy restart app

# Laravelプロジェクトの新規作成
setup:
	@if [ ! -d $(SRC_DIR)/vendor ]; then \
		mkdir $(SRC_DIR); \
		make up; \
		docker compose exec $(APP_SERVER) composer create-project --prefer-dist "laravel/laravel=12.*" .; \
		docker compose cp ./docker-config/php/.env.laravel $(APP_SERVER):/var/www/html/.env; \
		docker compose exec $(APP_SERVER) php artisan key:generate; \
		docker compose exec $(APP_SERVER) php artisan migrate; \
		docker compose exec $(APP_SERVER) chmod -R 777 storage bootstrap/cache; \
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
login-app:
	docker compose exec $(APP_SERVER) sh

# ------------------------------
# 参照コード用環境コマンド (ref-)
# ------------------------------

REF_SRC_DIR := src_ref
REF_APP_SERVER := ref-app-server

# Laravelプロジェクトの新規作成
setup-ref:
	@if [ ! -d $(REF_SRC_DIR)/vendor ]; then \
		mkdir $(REF_SRC_DIR); \
		make up-ref; \
		docker compose -f compose.ref.yml exec $(REF_APP_SERVER) composer create-project --prefer-dist "laravel/laravel=12.*" .; \
		docker compose -f compose.ref.yml cp ./docker-config/php/.env.laravel $(REF_APP_SERVER):/var/www/html/.env; \
		docker compose -f compose.ref.yml exec $(REF_APP_SERVER) php artisan key:generate; \
		docker compose -f compose.ref.yml exec $(REF_APP_SERVER) php artisan migrate; \
		docker compose -f compose.ref.yml exec $(REF_APP_SERVER) chmod -R 777 storage bootstrap/cache; \
	else \
		echo "-> Laravelプロジェクトが存在するため、インストールをスキップしました。"; \
	fi

# docker-compose基本コマンド
build-ref:
	docker compose -f compose.ref.yml build
up-ref:
	docker compose -f compose.ref.yml up -d
stop-ref:
	docker compose -f compose.ref.yml stop
start-ref:
	docker compose -f compose.ref.yml start
down-ref:
	docker compose -f compose.ref.yml down --remove-orphans
down-v-ref:
	docker compose -f compose.ref.yml down --remove-orphans -v
destroy-ref:
	docker compose -f compose.ref.yml down --remove-orphans -v --rmi all
restart-ref:
	@make down
	@make up

# コンテナログイン
login-ref:
	docker compose -f compose.ref.yml exec $(REF_APP_SERVER) sh

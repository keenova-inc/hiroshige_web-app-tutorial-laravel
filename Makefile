# docker-compose基本コマンド
build:
	docker compose build
build-no-cache:
	docker compose build --no-cache
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
	docker compose exec app sh
web:
	docker compose exec web sh
db:
	docker compose exec db sh
redis:
	docker compose exec redis bash
front:
	docker compose exec front bash

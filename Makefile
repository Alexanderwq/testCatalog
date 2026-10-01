init:
	docker compose build
	docker compose up -d --wait
	docker compose exec php-fpm php database/seed.php

up:
	docker compose up

down:
	docker compose down
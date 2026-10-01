## Запуск проекта

## http://localhost:8080/

### Через Makefile

```bash
make init
```

### Вручную

1. Собрать образы:
```bash
   docker compose build
```
2. Поднять контейнеры:
```bash
   docker compose up -d
```
3. Поднять контейнеры:
```bash
   скопировать .env.example в .env
```
4. Заполнить базу и дождаться завершения:
```bash
   docker compose exec php-fpm php database/seed.php
```
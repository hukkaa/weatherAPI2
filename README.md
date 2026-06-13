# Yii2 Weather API + PostgreSQL

Учебный проект на тему **«Погода в любом городе мира»**.

Проект сделан на Yii2, запускается в Docker, использует PostgreSQL и сохраняет историю запросов погоды в таблицу `weather_history`.

## Что внутри

- Yii2 web-приложение.
- API endpoint: `/api/weather?city=Москва`.
- Интерактивная страница API-тестера: `/api`.
- PostgreSQL таблица «История запросов».
- Страницы:
  - Home — 3 картинки и форма поиска погоды.
  - About — 5 картинок и описание проекта.
  - Contact — 6 картинок и описание API/контактов проекта.
  - API-тестер — отдельная страница, где можно вводить город и смотреть JSON-ответ.
  - История запросов — таблица сохранённых поисков.
- Погода берётся через Open-Meteo API без API-ключа.

## Запуск

```bash
unzip yii2-weather-api.zip
cd yii2-weather-api

docker compose up -d --build

docker compose exec app php yii migrate --interactive=0
```

Открыть сайт:

```text
http://localhost:8090
```

Открыть страницу API-тестера:

```text
http://localhost:8090/api
```

Проверить API напрямую:

```text
http://localhost:8090/api/weather?city=Tokyo
http://localhost:8090/api/weather?city=Москва
http://localhost:8090/api/weather?city=Париж
```

История запросов:

```text
http://localhost:8090/history
```

## Если порт 8090 занят

Открой `docker-compose.yaml` и поменяй строку:

```yaml
ports:
  - "8090:80"
```

Например:

```yaml
ports:
  - "8091:80"
```

Потом перезапусти:

```bash
docker compose down
docker compose up -d --build
```

## Данные PostgreSQL

- Host внутри Docker: `db`
- Внешний порт PostgreSQL: `5434`
- Database: `weather_db`
- User: `weather_user`
- Password: `weather_password`

## Полезные команды

Посмотреть контейнеры:

```bash
docker compose ps
```

Посмотреть логи приложения:

```bash
docker compose logs -f app
```

Посмотреть логи базы:

```bash
docker compose logs -f db
```

Остановить проект:

```bash
docker compose down
```

Остановить и удалить данные PostgreSQL:

```bash
docker compose down -v
```

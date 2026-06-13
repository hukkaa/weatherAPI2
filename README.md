# Yii2 Weather API + PostgreSQL

Учебный проект на тему **«Погода в любом городе мира»**.

Проект сделан на Yii2, запускается в Docker, использует PostgreSQL и сохраняет историю запросов погоды в таблицу `weather_history`.

## Что внутри

- Yii2 web-приложение.
- API endpoint: `/api/weather?city=Москва`.
- PostgreSQL таблица «История запросов».
- Страницы:
  - Home — 3 картинки и форма поиска погоды.
  - About — 5 картинок и описание проекта.
  - Contact — 6 картинок и описание API/контактов проекта.
  - История запросов — таблица сохранённых поисков.
- Погода берётся через Open-Meteo API без API-ключа. Если Open-Meteo временно отдаёт HTTP 429, проект переключается на дополнительный API `wttr.in` и использует кэш на 10 минут.

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

## Исправление для Render: HTTP 429

На бесплатном Render несколько пользователей могут выходить во внешний интернет с общих IP-адресов. Из-за этого Open-Meteo иногда возвращает HTTP 429 — ограничение количества запросов. В этой версии добавлены:

- запросы через PHP cURL;
- кэш результатов на 10 минут в `runtime/weather-cache`;
- автоматический fallback на `wttr.in`, если Open-Meteo временно недоступен или отдаёт HTTP 429.

Если после деплоя Render всё ещё показывает старую ошибку, очисти поле `Settings → Docker Command` и сделай `Manual Deploy → Deploy latest commit`.

#!/bin/sh
set -e

# Render запускает только один Docker-контейнер приложения.
# Перед стартом Apache пробуем применить миграции Yii2, чтобы создалась таблица истории запросов.
# Если база ещё несколько секунд недоступна, делаем несколько попыток.

MIGRATED=0
for i in 1 2 3 4 5 6 7 8 9 10; do
  echo "Running Yii2 migrations, attempt $i..."
  if php yii migrate --interactive=0; then
    MIGRATED=1
    break
  fi
  echo "Migration failed, waiting 5 seconds before retry..."
  sleep 5
done

if [ "$MIGRATED" != "1" ]; then
  echo "Migrations failed after 10 attempts. Starting Apache anyway..."
fi

exec apache2-foreground

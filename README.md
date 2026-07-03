# URL Shortener
## Ссылка - https://s.soupik.ru

## Описание проекта

Небольше веб приложение которое позволяет пользователям создавать короткие ссылки, отслеживать переходы и управлять своими ссылками через личный кабинет.

---

##  Технологический стек

- **Backend:** Laravel 13, PHP 8.4
- **Database:** PostgreSQL 17
- **Cache/Queue:** Redis 7
- **Admin Panel:** Filament v3
- **Queue Monitoring:** Laravel Horizon
- **Containerization:** Docker, Docker Compose
- **Task Runner:** Taskfile
- **Web Server:** Nginx

---

## Структура проекта

```
laravel-url-shortener/
app
├── Events
│   └── ShortUrl
│       └── ShortUrlClicked.php # Событие клика(редирект)
├── Filament
│   └── Resources 
│       ├── ShortUrlResource # Личный кабиент
│       │   ├── Pages
│       │   ├── RelationManagers
│       │   └── Widgets
│       └── ShortUrlResource.php
├── Http
│   └── Controllers
│       ├── Controller.php
│       └── web
│           └── ShortUrl # редирект по короткой ссылке
│               └── ShortUrlController.php
├── Jobs
│   └── ShortUrl # Post-response operations
│       └── ProcessShortUrlClickJob.php
├── Listeners
│   └── ShortUrl # Lisener события ShortUrlClicked
│       └── ClickListener.php
├── Models
├── Observers
│   └── ShortUrl # очистка/обновление cache
│       └── ShortUrlObserver.php
├── Policies # правила доступа
│   └── ShortUrlPolicy.php
├── Providers
├── Services
│   └── ShortUrl # Serive Layer
│       ├── Dto
│       │   └── ShortUrlClickDto.php
│       └── ShortUrlService.php # оброботка запрос по короткой ссылке
└── Support
    └── Dto # воспомогательный модуль
bootstrap
config # всякие конфиги
database
├── factories
├── migrations
└── seeders
docker #dev/prod окружение
├── nginx
│   ├── nginx.conf
│   ├── nginx.prod.conf
│   ├── site.conf
│   └── site.prod.conf
├── php
│   ├── Dockerfile
│   ├── Dockerfile.prod
│   ├── php-fpm.prod.conf
│   ├── php.ini
│   ├── php.prod.ini
│   └── prod
│       ├── Dockerfile
│       └── php-fpm.conf
└── supervisor
    └── supervisord.prod.conf
resources # всякие статические ресурсы
routes
└── web.php
tests # тесты
├── Feature
│   └── ExampleTest.php
├── TestCase.php
└── Unit
    ├── app
    │   └── Support
    │       └── Dto
    │           └── DtoTest.php
    └── ExampleTest.php
```

---

## Установка и запуск

### Требования

- Docker & Docker Compose
- Task (https://taskfile.dev)
- Git

### Development (Локальная разработка)

```bash
# 1. Клонировать репозиторий
git clone https://github.com/emilversiontwo/laravel-url-shortener.git
cd laravel-url-shortener

# 2. Инициализировать окружение
task setup
```

Приложение будет доступно по адресу: http://localhost:${APP_PORT}

### Production (Деплой на сервер)

```bash
# 1. Клонировать репозиторий
git clone https://github.com/emilversiontwo/laravel-url-shortener.git
cd laravel-url-shortener

# 2. Инициализировать production окружение
task deploy:init

# 3. Отредактировать .env
nano .env

# 4. Запустить деплой
task deploy
```

---

## Команды Taskfile

### Development

| Команда                | Описание                                                                     |
| ---------------------- | ---------------------------------------------------------------------------- |
| `task setup`           | Инициализация проекта (копирование .env, установка зависимостей, миграции)   |
| `task up`              | Запуск всех контейнеров                                                      |
| `task down`            | Остановка всех контейнеров                                                   |
| `task artisan`         | Выполнить artisan команду (пример: `task artisan -- migrate`)                |
| `task composer`        | Выполнить composer команду (пример: `task composer -- require package/name`) |
| `task app-cache-clear` | Очистить кэш приложения                                                      |


### Production

| Команда               | Описание                                                                 |
| --------------------- | ------------------------------------------------------------------------ |
| `task deploy:init`    | Инициализация production окружения (копирование .env.production.example) |
| `task deploy`         | Полный деплой (сборка, запуск, миграции, оптимизация)                    |
| `task deploy:update`  | Обновление после `git pull` (пересборка, миграции, кэш)                  |
| `task deploy:logs`    | Показать production логи                                                 |
| `task deploy:stop`    | Остановить production                                                    |
| `task deploy:restart` | Перезапустить production                                                 |

---

## Архитектурные решения

### Обработка редиректов и статистики

**Проблема:** При каждом переходе по короткой ссылке нужно записывать статистику (IP, User-Agent, время).
Если делать это синхронно, редирект будет медленным под нагрузкой.

**Решение:** Использовать Event-Driven архитектуру с очередями:

- ShortUrlController -> shortUrlService -> редирект
    - (диспатчит событие)
- ShortUrlClicked (Event)
    - (слушает)
- ClickListener
    - (диспатчит Job)
- ProcessShortUrlClickJob (Queue)
    - (записывает в БД)
- ShortUrlClick (Model)

**Результат:** Редирект происходит мгновенно, статистика пишется асинхронно через Redis очередь.
---

## Endpoints

### Публичные маршруты

| Метод | URI          | Описание                    |
| ----- | ------------ | --------------------------- |
| GET   | `/`          | Редирект на /admin          |
| GET   | `/s/{alias}` | Редирект по короткой ссылке |

### Filament Admin Panel

| Метод | URI                            | Описание                                 |
| ----- | ------------------------------ | ---------------------------------------- |
| GET   | `/admin`                       | Логин в админку                          |
| GET   | `/admin/register`              | Регистрация                              |
| GET   | `/horizon`                     | Laravel Horizon UI (мониторинг очередей) |

---

## Переменные окружения

### Development (.env)

```env
APP_NAME="URL Shortener"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

APP_PORT=80

DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=url_shortener
DB_USERNAME=url_shortener
DB_PASSWORD=

REDIS_CLIENT=phpredis
REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379

CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

### Production (.env)

```env
APP_NAME="URL Shortener"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://s.soupik.ru

APP_PORT=8080

DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=url_shortener
DB_USERNAME=url_shortener
DB_PASSWORD=STRONG_PASSWORD_HERE

REDIS_CLIENT=phpredis
REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379

CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

HORIZON_PREFIX=url_shortener_horizon:
```

### Horizon UI

Доступен по адресу: `https://s.soupik.ru/horizon`

Показывает:
- Активные jobs
- Завершенные jobs
- Проваленные jobs
- Статистику очередей

---

## 🗄 База данных

### Таблицы

- `users` - пользователи
- `short_urls` - короткие ссылки
- `short_url_clicks` - статистика переходов

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Решение тестового задания

## Описание решаемой задачи

Необходимо разработать веб приложение, которое будет предоставлять API для получения курса криптовалюты Bitcoin:
BTC/USD, BTC/EUR и т.п.

API должен позволять получать данные биржевого курса валюты по часам и иметь возможность изменить диапазон вывода. Предполагайте, что целью использования API является построение графика курса валюты.

Приложение должно обладать функционалом периодического обновления курсов валют с реальной биржи. Формат хранения и источник данных можно выбрать самим.

Количество предоставляемых через API пар валют - не меньше 3-х.

Стэк технологий:
* Symfony 4.x или 5.х
* PHP 7.x
* База данных может быть выбрана любая, в том числе и nosql.

## Развертывание приложения

Приложение полностью работоспособно и может быть развернуто и протестировано.

1. Клонируйте репозиторий
1. При необходимости сконфигурируйте docker контейнеры.
    * переопределите параметры `docker/docker-compose.override.yaml`, если это необходимо
    * параметры php xdebug определяются в файле `docker/php-fpm/xdebug.ini`
    * конфигурация nginx находится в файле `docker/nginx/default.conf`
1. Скопируйте `.env` в `.env.local` и определите локальные переменные окружения
1. В командной строке перейдите в папку `/docker` и выполните `docker-compose up -d`
1. Перейдите внутрь контейнера с PHP `docker exec -it cphp sh` и выполните:
    * `composer install`
    * `php bin/console doctrine:database:create`
    * `php bin/console d:m:m`
    
API будет доступно по адресу http://currs.loc. Консольные команды необходимо выполнять изнутри контейнера с PHP:
`docker exec -it cphp sh`

## Генерация тестовых данных

Для проверки работы приложения была создана команда генерации тестовых данных

`php bin/console data:generate <pair>`

**Примечание**: Дефолтного `memory_limit` в 128M может не хватить для выполнения команды. Самый быстрый способ обойти
это, использовать флаг `php -d memory_limit=2G`.

Пример вызова: `php -d memory_limit=2G bin/console data:generate BTC/USD` 

## API получения курсов валют

Доступно по url `GET /graphs`.

### Пример запроса

http://currs.loc/graphs?step=18000&from=2021-12-08 00:00:00&to=2021-12-10 15:47:00&currency_pairs[0][base]=BTC&currency_pairs[0][quote]=USD&currency_pairs[1][base]=BTC&currency_pairs[1][quote]=EUR&currency_pairs[2][base]=BTC&currency_pairs[2][quote]=RUB

Параметры:

* `currency_pairs` - массив запрашиваемых валютных пар, пример: `currency_pairs[][base]=BTC&currency_pairs[][quote]=USD`
* `from` - начало запрашиваемого периода в формате `Y-m-d H:i:s`
* `to` - конец запрашиваемого периода в формате `Y-m-d H:i:s`
* `step` - шаг в секундах, через который необходимо получить курсы

### Пример ответа

```json
[
  {
    "base": "USD",
    "quote": "EUR",
    "rates": {
      "2021-12-12 12:00:00": 38.2,
      "2021-12-12 13:00:00": 38.6,
      "2021-12-12 14:00:00": 38.7
    }
  },
  {
    "base": "USD",
    "quote": "RUB",
    "rates": {
      "2021-12-12 12:00:00": 38.2,
      "2021-12-12 13:00:00": 38.6,
      "2021-12-12 14:00:00": 38.7
    }
  }
]
```

## Команда пополнения базы курсов валют данными биржи

`php bin/console rates:pull_current <pairs> [<datetime>]`

Пример вызова: `php bin/console rates:pull_current BTC/EUR,BTC/USD,BTC/RUB`

## Комментарии к коду и обоснование выбранного решения

### Этапы решения задачи

### 1. Проектирование контрактов репозитория, DTO и написание контроллера

На этом этапе был создан `CurrencyRateAggregateRepositoryInterface` и содержимое папки 
`src/DTO`. При этом мы не думаем о том, как будем хранить данные, только о бизнес-логике и поставленной задаче.
Выделение `CurrencyRateAggregateRepositoryInterface` соответствует принципам инверсии зависимостей и сегрегации
интерфейсов. А выделение DTO вместо привычных для Symfony доктриновских сущностей, собирающих в себе метаданные
структуры БД, группы сериализации и валидацию, дает нам гибкость, фреймворконезависимость и возможность отдельно
управлять сериализацией (DTO риквестов и риспонсов), валидацией (DTO риквестов) и хранением данных (о котором мы
пока не думаем).

В качестве реализации `CurrencyRateAggregateRepositoryInterface` сделана заглушка, возвращающая рандомные курсы, и был
написан контроллер для проверки работоспособности и удобства разработанных контрактов.

### 2. Реализация репозиория

На этом этапе в проект подключается Doctrine. Слой ORM в данном приложении использован только для описания структуры
хранения данных и может быть легко выпилен. Был создан `CurrencyRateDBALAggregateRepository`, опирающийся на
`doctrine/dbal` и заменяющий созданную ранее заглушку. С тем же успехом мы можем написать другие реализации
репозитория, опирающиеся на слой ORM, или получающие данные из внешних сервисов.

### 3. Валидация

После проверки работоспособности DBAL репозитория в контроллер была добавлена валидация и обработка исключений.

### 4. Контракты и команда на получение курсов с биржи

Беглый поиск дал большое количество открытых источников курсов валют, передаваемых как по АПИ, так и, например, в
XML файлах. Для получения единичного курса валюты на конкретную дату был создан `CurrencyRateReadRepositoryInterface`,
а для записи полученных данных в нашу базу - `CurrencyRateWriteRepositoryInterface`. Далее была создана
команда `PullCurrentRatesCommand` опирающаяся на эти интерфейсы. В текущей реализации
`CurrencyRateReadRepositoryInterface` реализован в виде простой заглушки для сокращения времени выполнения задания.
Возможны следующие варианты реальных реализаций:

* Сделать реализацию `CurrencyRateReadRepositoryInterface`, которая будет делать один или несоклько асинхронных
запросов по API для получения курсов на конкретный момент времени.
  
* Сделать реализацию `CurrencyRateAggregateRepositoryInterface`, которая будет парсить XML файл, доставая из него только
курсы на заданный интервал времени. Например, файл содержит курсы на каждую минуту, а мы берем оттуда только курсы на
каждый час.
  
### Прочие комментарии

* По условиям задачи необходимо использовать PHP 7, поэтому вместо атрибутов использованы `doctrine/annotations`.
* Сервисный слой в приложении так и не появился, т. к. в условиях задачи нет никакой бизнес- или технической логики.
* В проекте нет юнит-тестов, т. к. покрывать ими практически нечего. Функциональные и интеграционные тесты считаю слишком
дорогим "удовольствием", а их использование оправданным только в особых случаях.
* В проекте не сделано документации АПИ для сокращения времени выполнения задания.
* Не сделан enum валюты, который мог бы пригодиться для валидации.
* Для оперирования большими объемами данных (курсы валют на каждую секунду за большой промежуток времени) для оптимизации
будет лучше вместо DTO использовать простые массивы.
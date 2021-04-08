Class Room
=================

Тестовый проект: класс со студентами.

#Требования

 - Docker

#Установка

 **Клонирование**

```bash
$> git clone git@github.com:Chervyak375/class-room.git
```

или [**скачать репозиторий и распаковать**](https://github.com/Chervyak375/class-room/archive/refs/heads/master.zip)

 **Создание докер контейнеров**<br>
 *_Открыть командную строку в папку проекта!_*

```bash
$> docker-compose build
```

 **Запуск докер контейнеров**<br>

```bash
$> docker-compose up
```

*_Дождаться загрузки всех контейнеров!_*<br>
*_(Должна появиться строка с текстом:_* "ready for connections"*_)_*
![containers_ready](https://i.ibb.co/pRBgqTw/image.png)

 **Обновление зависимостей**<br>
*_Открыть новую командную строку в папке проекта!_*

```bash
$> docker exec -ti php sh -c "php /usr/local/bin/composer.phar install"
```

 **Применение миграций**
 
```bash
$> docker exec -ti php sh -c "./yii migrate --interactive=0"
```

  **WebSocket**<br>
 Запуск WebSocket сервера.<br>
  *_Открыть новую командную строку в папке проекта!_*
 ```bash
 $> docker exec -ti server sh -c "./yii server/start"
 ```
 
#Конфигурация

 **MySQL**<br>
В файле конфигурации ***config/db.php*** указать данных подключения для докер контейнера.

 **Redis**<br>
В файле конфигурации ***config/redis.php*** указать данных подключения внешней бд.

 **Cookie**<br>
Вставьте секретный ключ в ***config/web.php*** (если он пуст) - это требуется для проверки файлов cookie
```php
'request' => [
            'cookieValidationKey' => ' SECRETKEY',
        ],
```

#Использование

Сайт расположен на **localhost:8088**

#Описание

**Видео**

![screenshot_1](https://s4.gifyu.com/images/2021-04-08-07-26-30-1.gif)

**Скриншоты**

![screenshot_1](https://i.ibb.co/wY38wRS/image.png)
# 
![screenshot_2](https://i.ibb.co/FHC4zCG/image.png)
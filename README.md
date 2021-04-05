Class Room
=================

Тестовый проект: класс со студентами.

#Установка

 **Клонирование**

```bash
$> git clone git@github.com:Chervyak375/class-room.git
```
 **Обновление зависимостей**

```bash
$> composer install
```
 **Применение миграций**
 
```bash
$> yii migrate
```
 
#Конфигурация

 **MySQL**<br>
В файле конфигурации ***config/db.php*** указать данных подключения:

```php
'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=127.0.0.1;dbname=yii2basic',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',
```

 **Redis**<br>
В файле конфигурации ***config/redis.php*** указать данных подключения:

```php
'redis' => [
            'hostname' => 'redis-10893.c135.eu-central-1-1.ec2.cloud.redislabs.com',
            'port' => 10893,
            'password' => "PASSWORD",
            'database' => 0,
        ],
```

 **Cookie**<br>
Вставьте секретный ключ в ***config/web.php*** (если он пуст) - это требуется для проверки файлов cookie
```php
'request' => [
            'cookieValidationKey' => ' SECRETKEY',
        ],
```

 **WebSocket**<br>
Запуск WebSocket сервера. Порт по умолчанию *3012*.
```bash
$> yii server/start [port]
```

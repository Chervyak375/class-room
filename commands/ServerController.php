<?php
namespace app\commands;

use app\daemons\ChatServer;
use app\daemons\CommandsServer;
use app\daemons\RoomServer;
use consik\yii2websocket\WebSocketServer;
use yii\console\Controller;

class ServerController extends Controller
{
    public function actionStart($port = 3012)
    {
        //sleep(5);
        $server = new RoomServer();
        $server->port = $port;

        $server->on(WebSocketServer::EVENT_WEBSOCKET_OPEN_ERROR, function($e) use($server) {
            echo "Error opening port " . $server->port . "\n";
            /**
             * @var \Exception $exp
             */
            $exp = $e->exception . "\n";
            echo $exp->getMessage();
            $server->start();
        });

        $server->on(WebSocketServer::EVENT_WEBSOCKET_OPEN, function($e) use($server) {
            echo "Server started at port " . $server->port . "\n";
        });

        $server->start();
    }
}
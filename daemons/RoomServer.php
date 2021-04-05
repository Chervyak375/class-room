<?php

namespace app\daemons;

use app\classes\PubSubManager;
use app\models\User;
use app\models\UserIdentity;
use consik\yii2websocket\events\WSClientEvent;
use consik\yii2websocket\WebSocketServer;
use phpDocumentor\Reflection\Types\True_;
use Ratchet\ConnectionInterface;

class RoomServer extends WebSocketServer
{
    /**
     * @var PubSubManager $pubSubManager
     */
    protected $pubSubManager;

    public function init()
    {
        parent::init();
        $this->pubSubManager = new PubSubManager();
        $this->on(self::EVENT_CLIENT_DISCONNECTED, function (WSClientEvent $e) {
            $user_id = $e->client->name;
            $user = User::findOne($user_id);
            $user->setHand(false);
            $user->setOnline(false);
            $user->update();
            $this->pubSubManager->publish($e->client, 'user_offline', json_encode([
                'id' => $user_id,
                'isHandRaised' => false,
                'online' => false,
            ]));
            $this->pubSubManager->broker();
        });
        $this->on(self::EVENT_CLIENT_MESSAGE, function (WSClientEvent $e) {
            $from = $e->client;
            $msg = $e->message;
            $json = json_decode($msg, true);
            $request = $json['request'];
            $message = $json['message'];
            $channel = $json['channel'];
            $msg = json_decode($message, true);

            $e->client->name = $msg['id'];

            //echo 'INPUT MSG: ' . $msg . "\n";

            switch ($request) {
                case 'PUBLISH':
                {
                    $this->pubSubManager->publish($from, $channel, $message);
                    break;
                }
                case 'SUBSCRIBE':
                {
                    $this->pubSubManager->subscribe($from, $channel);
                    break;
                }
            }

            $this->pubSubManager->broker();
        });

        $this->pubSubManager->setHandler('user_online', function ($message) {
            $json = json_decode($message, true);
            $user = User::findOne($json['id']);
            $user->setOnline(true);
            $user->update();
        });
    }

}
<?php

namespace app\daemons;

use app\classes\PubSubManager;
use app\models\User;
use app\models\UserIdentity;
use consik\yii2websocket\events\WSClientErrorEvent;
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
            if(empty($user_id))
                return;
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
        $this->on(self::EVENT_WEBSOCKET_OPEN, function (){
            $users = User::find()->orderBy('id')->all();
            foreach ($users as $user)
            {
                $user->setOnline(false);
                echo "1234DS\n";
                $user->setHand(false);
            }
        });
        $this->on(self::EVENT_CLIENT_ERROR, function (WSClientErrorEvent $e) {
            echo "ERROR!\n";
            echo $e->exception->getMessage();
            echo "DB ACTIVE: " . \Yii::$app->db->isActive . "\n";
            \Yii::$app->db->open();
//           $this->stop();
//           $this->start();
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

            //echo 'INPUT MSG: ' . json_encode($json) . "\n";

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
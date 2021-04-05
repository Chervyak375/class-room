<?php

namespace app\classes;

use Ratchet\ConnectionInterface;

class PubSubManager {
    protected $channels = [
        'user_state_changed' => [
            'message' => '',
            'subscribers' => []
        ],
        'user_offline' => [
            'message' => '',
            'subscribers' => []
        ],
        'user_online' => [
            'message' => '',
            'subscribers' => []
        ],
        'user_profile_updated' => [
            'message' => '',
            'subscribers' => [],
        ]
    ];

    public function subscribe($subscriber, $channel) {
        array_push($this->channels[$channel]['subscribers'], $subscriber);
    }

    public function publish($publisher, $channel, $message) {
        $this->channels[$channel]['message'] = $message;
    }

    public function broker()
    {
        foreach ($this->channels as $channelName => $channel)
        {
            if(array_key_exists($channelName, $this->channels))
            {
                $channelObj = &$this->channels[$channelName];
                if($channelObj['message'])
                {
                    if(array_key_exists('handler', $channelObj))
                    {
                        $this->execHandler($channelName);
                    }
                    foreach ($channelObj['subscribers'] as $subscriber)
                    {
                        /**
                         * @var ConnectionInterface $subscriber
                         */
                        echo 'SEND!';
                        $subscriber->send(json_encode([
                            'channel' => $channelName,
                            'message' => $channelObj['message'],
                        ]));
                    }
                    $channelObj['message'] = '';
                }
            }
        }
    }

    public function setHandler($channelName, $handler)
    {
        $this->channels[$channelName]['handler'] = $handler;
    }

    protected function execHandler($channelName)
    {
        echo 'GO TO EXET: ' . $channelName . "\n";
        $channelMessage = $this->channels[$channelName]['message'];
        $this->channels[$channelName]['handler']($channelMessage);
    }
}
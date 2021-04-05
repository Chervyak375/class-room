<?php
namespace app\widgets\membersList;

use app\models\User;
use app\models\UserIdentity;
use Yii;

/**
 * Alert widget renders a message from session flash. All flash messages are displayed
 * in the sequence they were assigned using setFlash. You can set message as following:
 *
 * ```php
 * Yii::$app->session->setFlash('error', 'This is the message');
 * Yii::$app->session->setFlash('success', 'This is the message');
 * Yii::$app->session->setFlash('info', 'This is the message');
 * ```
 *
 * Multiple messages could be set as follows:
 *
 * ```php
 * Yii::$app->session->setFlash('error', ['Error 1', 'Error 2']);
 * ```
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @author Alexander Makarov <sam@rmcreative.ru>
 */
class MembersList extends \yii\bootstrap\Widget
{
    public $isAjax=false;

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $users = UserIdentity::find()
            ->orderBy([
                'first_name' => SORT_ASC,
                'last_name' => SORT_ASC,
            ])
            ->all();

        $users_online = [];
        $users_offline = [];

        foreach ($users as $user)
        {
            /**
             * @var User $user
             */
            if($user->isOnline())
                array_push($users_online, $user);
            else
                array_push($users_offline, $user);
        }

        $users = array_merge($users_online, $users_offline);

        return $this->render('block', [
            'users' => $users,
            'isAjax' => $this->isAjax,
        ]);
    }
}

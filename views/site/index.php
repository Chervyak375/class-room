<?php

/* @var $this yii\web\View */
/* @var $success bool */
/* @var $afterLogin bool */
/* @var $user \app\models\UserIdentity */
/* @var $model app\models\EditProfileForm */

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;
use yii\data\ActiveDataProvider;
use \app\models\User;
use \app\widgets\membersList\MembersList;
use app\assets\AppAsset;
use \app\assets\RoomAsset;

$this->title = 'My Yii Application';
AppAsset::register($this);
RoomAsset::register($this);
?>
<div class="site-index">

    <?= MembersList::widget() ?>

</div>

<?= $this->render('..\profile\index', ['model' => $model]); ?>

<script>
    var USER_ID = <?= Yii::$app->session->get('user_id') ?>;
    var URL_SITE_MEMBERS = '<?= Url::to(['site/members']); ?>';
</script>
<?php
/* @var $this yii\web\View */
/* @var $success bool */

/* @var $model \app\models\EditProfileForm */

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\widgets\Pjax;

/**
 * @var \app\models\UserIdentity $user
 */
$user = Yii::$app->user->identity;

?>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>

<?php
$pjax = Pjax::begin([
    'enablePushState' => false,
]);
?>
<?php $form = ActiveForm::begin([
    'id' => 'edit-profile-form',
    'action' => ['profile/edit'],
    'options' => [
        'data-pjax' => true,
    ]
]); ?>
<?
Modal::begin([
    'header' => '<h4>Edi profile</h4>',
    'footer' => (
        Html::submitButton('Save', ['class' => 'btn btn-primary']) .
        Html::button('Cancel', ['class' => 'btn', 'data-dismiss' => 'modal'])
    ),
    'options' => [
        'id' => 'editProfile',
        'display' => 'none',
    ],
    'closeButton' => false
]);
?>

<?= $form->field($model, 'first_name') ?>

<?= $form->field($model, 'last_name') ?>

<?= $form->field($model, 'email') ?>
<?
Modal::end();
ActiveForm::end();
?>
<?php if (Yii::$app->request->isPjax): ?>
    <script>
        $(document).ready(function () {
            $(".modal-backdrop.fade.in").remove();
            if (<?= json_encode($success) ?>) {
                publish('user_profile_updated', JSON.stringify({
                        id: <?= Yii::$app->session->get('user_id') ?>,
                        firstName: '<?= Html::encode($user->first_name) ?>',
                        lastName: '<?= Html::encode($user->last_name) ?>',
                        email: '<?= Html::encode($user->email) ?>',
                    })
                );
            } else {
                $('#editProfile').modal('show');
            }
        });
    </script>
<?php endif; ?>
<?php
Pjax::end();
?>

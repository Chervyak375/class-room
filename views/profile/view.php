<?php
/* @var $this yii\web\View */

/* @var \app\models\User $user */

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\widgets\Pjax;

?>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>

<?
Modal::begin([
    'header' => '<h4>View profile</h4>',
    'footer' => (
        '<div class="col-md-12 text-center">' .
        Html::button('Cancel', ['class' => 'btn', 'data-dismiss' => 'modal']) .
        '</div>'
    ),
    'options' => [
        'id' => 'viewProfile',
        'display' => 'none',
    ],
    'closeButton' => false
]);
?>

<?= Html::label($user->getAttributeLabel('first_name')) . ': ' . $user->first_name ?> </br>
<?= Html::label($user->getAttributeLabel('last_name')) . ': ' . $user->last_name ?> </br>
<?= Html::label($user->getAttributeLabel('email')) . ': ' . $user->email ?> </br>

<?
Modal::end();
?>
<script>
    $(document).ready(function () {
        $(".modal-backdrop.fade.in").remove();
        $('#viewProfile').modal('show');
    });
</script>

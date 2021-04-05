<?php
/**
 * @var \yii\db\BaseActiveRecord[] $users
 * @var bool $isAjax
 */

use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use backend\models\Standard;
use yii\helpers\Url;

?>

<?php if (!$isAjax): ?>
    <style>
        .ak-holder {
            height: 300px;
            overflow-x: auto;
            overflow-y: auto;
        }

        .ak-holder p {
            font-weight: bold;
            text-align: center;
            padding: 5px;
        }

        .ak-holder table {
            border: solid 1px #ccc;
            width: 100%;
        }

        .ak-holder table td {
            padding: 10px;
            white-space: nowrap;
        }

        .ak-holder table tbody tr {
            border-top: solid 1px #ccc;
        }

        .table-hover > tbody > tr.offline:hover > td,
        .offline > td {
            background-color: #efefef;
        }
        .me > td {
            background-color: #ecffff;
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<?php endif; ?>

    <div_ id="members">
        <h3>Members</h3>
        <div class="ak-holder">
            <table>
                <?php foreach ($users as $user): ?>
                    <?
                    /**
                     * @var \app\models\User $user
                     */
                    $isMe = $user->id == Yii::$app->session->get('user_id');
                    if($isMe)
                        $rowSelectCss = 'me';
                    else
                        $rowSelectCss = $user->isOnline() ? '' : 'offline';
                    ?>
                    <tr class="<?= $rowSelectCss ?>">
                        <td data-user-id="<?= $user->id ?>"><span class="glyphicon glyphicon-hand-up" name="user-hade"
                                                                  style="visibility: <?= $user->getIsHandUp() ? 'unset' : 'hidden' ?>"></span>
                            <text_ name="user-name"><?= $user->getName() ?></text_>
                            <span class="pull-right">
                <div class="dropdown">
                    <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                             class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                            <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                        </svg>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                        <?php if ($isMe): ?>
                            <li><a href="#" name="user-hand"
                                   onclick="Handing(<?= $user->id ?>)">Raise hand <?= $user->getIsHandUp() ? 'down' : 'up' ?></a></li>
                        <?php endif; ?>
                        <li><a href="#" onclick="ViewProfile(<?= $user->id ?>)">View profile</a></li>
                    </ul>
                </div>
                </span>
                        </td>
                    </tr>

                <?php endforeach; ?>

            </table>
        </div>
    </div_>

<?php if (!$isAjax): ?>
    <div_ id="modalBuffer">
    </div_>

    <script>
        $(function () {
            //add BT DD show event
            $(".dropdown").on("show.bs.dropdown", function () {
                var $btnDropDown = $(this).find(".dropdown-toggle");
                var $listHolder = $(this).find(".dropdown-menu");
                //reset position property for DD container
                $(this).css("position", "static");
                $listHolder.css({
                    "top": ($btnDropDown.offset().top + $btnDropDown.outerHeight(true)) + "px",
                    "left": $btnDropDown.offset().left + "px"
                });
                $listHolder.data("open", true);
            });
            //add BT DD hide event
            $(".dropdown").on("hidden.bs.dropdown", function () {
                var $listHolder = $(this).find(".dropdown-menu");
                $listHolder.data("open", false);
            });
            //add on scroll for table holder
            $(".ak-holder").scroll(function () {
                var $ddHolder = $(this).find(".dropdown")
                var $btnDropDown = $(this).find(".dropdown-toggle");
                var $listHolder = $(this).find(".dropdown-menu");
                if ($listHolder.data("open")) {
                    $listHolder.css({
                        "top": ($btnDropDown.offset().top + $btnDropDown.outerHeight(true)) + "px",
                        "left": $btnDropDown.offset().left + "px"
                    });
                    $ddHolder.toggleClass("open", ($btnDropDown.offset().left > $(this).offset().left))
                }
            })
        });
    </script>
    <script>
        function ViewProfile(id) {
            $.get('<?= Url::to(['profile/view']) ?>',
                {
                    id
                }).done(function (data) {
                $('#modalBuffer').html(data);
            });
        }

        function Handing(id) {
            $.get('<?= Url::to(['profile/handing']) ?>',
                {
                    id
                }).done(function (data) {
                publish('user_state_changed', JSON.stringify({
                        id: <?= Yii::$app->session->get('user_id') ?>,
                        isHandRaised: data['hand'],
                    })
                );
            });
        }
    </script>
<?php endif; ?>
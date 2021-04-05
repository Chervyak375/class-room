<?php

namespace app\controllers;

use app\models\EditProfileForm;
use app\models\User;
use app\models\UserIdentity;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class ProfileController extends \yii\web\Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['get'],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(Yii::$app->urlManager->createUrl('site/login'));
        }
        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        $model = new EditProfileForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('index', [
            'model' => $model,
        ]);
    }

    public function actionView($id)
    {
        $user = User::find()
            ->where(['id' => $id])
            ->one();
        $this->layout = false;
        return $this->render('view', [
            'user' => $user
        ]);
    }

    public function actionEdit()
    {
        $model = new EditProfileForm();
        $success = false;
        if ($model->load(Yii::$app->request->post()) && $model->edit()) {
            $success = true;
        }

        return $this->render('index', [
            'model' => $model,
            'success' => $success
        ]);
    }

    public function actionHanding()
    {
        /**
         * @var UserIdentity $user
         */
        $user = Yii::$app->user->identity;
        $newHand = !$user->getIsHandUp();
        $user->setHand($newHand);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return [
            'hand' => $newHand
        ];
    }
}

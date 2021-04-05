<?php

namespace app\controllers;

use app\classes\SessionUnhide;
use app\models\EditProfileForm;
use app\models\Online;
use app\models\UserState;
use app\models\User;
use app\models\UserIdentity;
use app\widgets\membersList\MembersList;
use Yii;
use yii\filters\AccessControl;
use yii\redis\Session;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use yii\web\UrlManager;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['get'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(Yii::$app->urlManager->createUrl('site/login'));
        }

        $model = new EditProfileForm();
        /**
         * @var UserIdentity $user
         */
        $user = Yii::$app->user->identity;
        $model->attributes = $user->getAttributes();
        return $this->render('index', [
            'model' => $model,
            'user' => $user,
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionMembers()
    {
        if (Yii::$app->user->isGuest || !Yii::$app->request->isAjax) {
            return $this->goHome();
        }

        $this->layout = false;
        return MembersList::widget();
    }
}

<?php

namespace app\controllers;


use phpDocumentor\Reflection\Types\False_;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\httpclient\Client;
use yii\httpclient\JsonParser;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

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
                    'logout' => ['post'],
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
     * @param $limit
     * @param $offset
     * @return void
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    private function getListofPokemon($limit, $offset): array
    {
        $pokemons = [];
        $client = new Client();
        $response = $client->get('https://pokeapi.co/api/v2/pokemon/', ['limit' => $limit, 'offset' => $offset])
            ->send();
        $decoderesponse = Json::decode($response->content);

        foreach ($decoderesponse['results'] as $pokemon) {
            $responsedetail = $client->get($pokemon['url'])->send();
            $details = Json::decode($responsedetail->content);
            $pokemons[$details['id']]['name'] = $details['name'];
            $pokemons[$details['id']]['picture'] = $details['sprites']['other']['dream_world']['front_default'];
            foreach ($details['types'] as $key => $type) {
                $pokemons[$details['id']]['types'][$key] = $type['type']['name'];
            }

        }
        return $pokemons;
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {


        $pokeinlist = $this->getListofPokemon(21, 0);


        //$client = new Client(['baseUrl' => 'https://pokeapi.co/api/v2/pokemon/']);
        //$response = $client->setMethod('GET')->setUrl('1')->send();


        return $this->render('index', ['response' => $pokeinlist]);
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

        $model->password = '';
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

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}

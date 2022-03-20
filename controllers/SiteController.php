<?php

namespace app\controllers;


use phpDocumentor\Reflection\Types\False_;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\httpclient\Client;
use yii\httpclient\Exception;
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

        if ($response->isOk) {
            $decoderesponse = Json::decode($response->content);

            foreach ($decoderesponse['results'] as $pokemon) {
                $responsedetail = $client->get($pokemon['url'])->send();
                $pokemons = $this->getPokemonDetails($responsedetail, $pokemons);
            }
            return $pokemons;

        }else{
            throw new Exception($response->content, $response->statusCode);
        }
    }

    /**
     * @param $searchstring
     * @return array
     * @throws \yii\httpclient\Exception
     */
    private function getSinglePokemon($searchstring): array
    {
        $pokemons = [];
        $client = new Client();
        $response = $client->get('https://pokeapi.co/api/v2/pokemon/' . $searchstring)
            ->send();

        if ($response->isOk) {
            $pokemons = $this->getPokemonDetails($response, $pokemons);

            return $pokemons;
        } else {
            throw new Exception($response->content, $response->statusCode);
        }
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $pokeinlist = [];
        try {
            $search = Yii::$app->request->post('searchstring');
            if (!empty($search)) {
                $pokeinlist = $this->getSinglePokemon($search);
            } else {
                $pokeinlist = $this->getListofPokemon(52, 0);

            }
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('danger', $e->getMessage());
        }


        return $this->render('index', ['response' => $pokeinlist, 'value' => $search]);
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

    /**
     * @param \yii\httpclient\Response $response
     * @param array $pokemons
     * @return array
     */
    private function getPokemonDetails(\yii\httpclient\Response $response, array $pokemons): array
    {
        $decoderesponse = Json::decode($response->content);

        $pokemons[$decoderesponse['id']]['name'] = $decoderesponse['name'];
        $pokemons[$decoderesponse['id']]['picture'] = $decoderesponse['sprites']['other']['dream_world']['front_default'];
        foreach ($decoderesponse['types'] as $key => $type) {
            $pokemons[$decoderesponse['id']]['types'][$key] = $type['type']['name'];
        }
        return $pokemons;
    }
}

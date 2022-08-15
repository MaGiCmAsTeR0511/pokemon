<?php

namespace app\controllers;

use app\models\Pokemon;
use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\httpclient\Client;
use yii\httpclient\Exception;
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
            $return = [];
            $decoderesponse = Json::decode($response->content);
            $link['totalCount'] = $decoderesponse['count'];
            $link['next'] = $decoderesponse['next'];
            $link['previous'] = $decoderesponse['previous'];
            $return['link'] = $link;
            foreach ($decoderesponse['results'] as $pokemon) {
                $responsedetail = $client->get($pokemon['url'])->send();
                $return['pokemon'][] = $this->getPokemonDetails($responsedetail, $pokemons);
            }
            return $return;

        } else {
            throw new Exception($response->content, $response->statusCode);
        }
    }

    /*private function getEvolutionsOfPokemon($id)
    {
        $evolutions = [];
        $client = new Client();
        $response = $client->get('https://pokeapi.co/api/v2/evolution-chain/' . $id . '/')
            ->send();
        if ($response->isOk) {
            $chain = Json::decode($response->content);
            if(!empty($chain['chain']['evolves_to'])){
                //foreach($chain['chain']['evolves_to'])
            }
           // echo '<pre>' . print_r($chain['chain']['evolves_to'], true) . '</pre>';
            //die();

            return $chain;
        } else {
            throw new Exception($response->content, $response->statusCode);
        }
    }*/

    private function getEvolutions($evolvesto)
    {

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
            $pokemon['pokemon'] = [];
            $pokemon['pokemon'][] = $this->getPokemonDetails($response, $pokemons);

            return $pokemon;
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
        $limit = 18;
        $offset = 0;
        $pokeinlist['pokemon'] = [];
        $pagination = new Pagination();
        try {
            $search = Yii::$app->request->post('searchstring');

            if (!empty($search)) {
                $pokeinlist = $this->getSinglePokemon(strtolower($search));

            } else {

                $offset = 18 * (Yii::$app->request->get('page') - 1);
                $offset = max($offset, 0);

                $pokeinlist = $this->getListofPokemon($limit, $offset);
                $pagination = new Pagination(['totalCount' => $pokeinlist['link']['totalCount'], 'pageSize' => $limit, 'pageSizeParam' => false]);
            }
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('danger', $e->getMessage());
        }

        return $this->render('index', ['pokemons' => $pokeinlist['pokemon'], 'value' => $search, 'pagination' => $pagination]);
    }

    public function actionPage($page){

    }

    /**
     * @param $id
     * @return string
     * @throws Exception
     */
    public function actionDetails($id)
    {
        $pokemon = $this->getSinglePokemon($id);
        /*$evolutions = $this->getEvolutionsOfPokemon($id);*/

        return $this->renderAjax('detail', ['pokemon' => $pokemon['pokemon'][0]]);
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
    private function getPokemonDetails(\yii\httpclient\Response $response, array $pokemons): object
    {

        $pokemon = new Pokemon();
        $decoderesponse = Json::decode($response->content);
        $pokemon->id = $decoderesponse['id'];
        $pokemon->name = $decoderesponse['name'];
        $pokemon->picture = $decoderesponse['sprites']['other']['dream_world']['front_default'];
        $pokemon->height = $decoderesponse['height'] / 10;
        $pokemon->weight = $decoderesponse['weight'] / 10;

        foreach ($decoderesponse['types'] as $key => $type) {
            $pokemon->types[] = $type['type']['name'];
        }

        foreach ($decoderesponse['moves'] as $key => $move) {
            $pokemon->moves[] = $move['move']['name'];
        }

        //$pokemon->evolutions = $this->getEvolutionsOfPokemon($pokemon->id);
        //var_dump($pokemon->evolutions);
        return $pokemon;
    }

    private function getEvolutionsOfPokemon($id)
    {
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl('https://pokeapi.co/api/v2/evolution-chain/' . $id)
            ->send();
        if ($response->isOk) {
            $decoderesponse = Json::decode($response->content);
            $evolutions = [];
            foreach ($decoderesponse['evolution_chain']['evolves_to'] as $key => $evolution) {
                $evolutions[] = $evolution['species']['name'];
            }
            return $evolutions;
        } else {
            throw new Exception($response->content, $response->statusCode);
        }
    }
}

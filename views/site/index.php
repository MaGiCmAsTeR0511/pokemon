<?php
/** @var yii\web\View $this */

/** @var  $pokemons \app\models\Pokemon [] */

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use app\widgets\Alert;
use yii\bootstrap4\Modal;
use yii\bootstrap4\LinkPager;
use edwinhaq\simpleloading\SimpleLoading;

$this->title = 'Pokemon';
Modal::begin([
    'id' => 'pokemondetailsmodal',
    'title' => '',
]);

Modal::end();

?>

<body>
<?php $form = ActiveForm::begin() ?>
<div class="form-group">
    <?= Html::textInput('searchstring', $value, ['class' => 'form-control']) ?>
</div>
<?= Alert::widget() ?>
<div class="container">
    <div class="align-content-center">
        <?= LinkPager::widget(['pagination' => $pagination]); ?>
    </div>
    <div class="row">
        <?php
        if (!empty($pokemons)):
        foreach ($pokemons

        as $id => $pokemon):
        ?>
        <div class="col-sm-2 form-group pokecard" data-id="<?= $pokemon->id ?>"
        "
        data-name="<?= $pokemon->name ?>">
        <div class="card card-<?= $pokemon->types[0] ?>" style="width: 10rem;">
            <img class="picture-overview" src="<?= $pokemon->picture ?> " class="card-img-top"
                 alt="<?= $pokemon->name ?>">
            <?php $class = []; ?>

                <?php foreach ($pokemon->types as $type):
                    switch ($type) {

                        case 'grass' :
                            $class[] = "#78C855";
                            break;
                        case 'poison':
                            $class[] = "#A040A0";
                            break;

                        case 'fire':
                            $class[] = "#F08030";
                            break;

                        case 'water':
                            $class[] = "#6890F0";
                            break;

                        case 'bug':
                            $class[] = "#A8B820";
                            break;

                        case 'flying':
                            $class[] = "#A890F0";
                            break;

                        case 'electric':
                            $class[] = "#F8D030";
                            break;

                        case 'ice':
                            $class[] = "#98D8D8";
                            break;

                        case 'fighting':
                            $class[] = "#C03028";
                            break;

                        case 'ground':
                            $class[] = "#E0C068";
                            break;

                        case 'psychic':
                            $class[] = "#F85888";
                            break;

                        case 'rock':
                            $class[] = "#B8A038";
                            break;

                        case 'ghost':
                            $class[] = "#705898";
                            break;

                        case 'dark':
                            $class[] = "#705848";
                            break;

                        case 'dragon':
                            $class[] = "#7038F8";
                            break;

                        case 'steel':
                            $class[] = "#B8B8D0";
                            break;

                        case 'fairy':
                            $class[] = "#F0B6BC";
                            break;

                        case 'normal':
                            $class[] = "#A8A878";
                            break;
                    }

                endforeach; ?>

            <div class="card-body" style="background-image: linear-gradient(45deg,<?php echo implode(',', $class) ;?> <?php if(count($class) < 2) : echo ", white"; endif?>)" id="<?= $pokemon->id ?>">
                <h5 class="card-title"><?= $pokemon->name ?></h5>
                <?php foreach ($pokemon->types as $type): ?>
                    <span class="badge badge-pill badge-<?= $type ?>"><?= $type ?></span>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <?php endforeach ?>
    <?php endif; ?>
</div>
<?php ActiveForm::end() ?>
</div>
</body>

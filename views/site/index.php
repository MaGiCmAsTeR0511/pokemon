<?php
/** @var yii\web\View $this */

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

SimpleLoading::widget();
?>


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
            foreach ($pokemons as $id => $pokemon):
                ?>
                <div class="col-sm-2 form-group pokecard" data-id="<?= $pokemon['id'] ?>"
                     data-name="<?= $pokemon['name'] ?>">
                    <div class="card card-<?= $pokemon['types'][0] ?>" style="width: 10rem;">
                        <img class="picture-overview" src="<?= $pokemon['picture'] ?> " class="card-img-top"
                             alt="<?= $pokemon['name'] ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= $pokemon['name'] ?></h5>
                            <?php foreach ($pokemon['types'] as $type): ?>
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

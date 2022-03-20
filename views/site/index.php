<?php
/** @var yii\web\View $this */

use yii\bootstrap4\ActiveForm;

$this->title = 'Pokemon';
?>
<div class="container">
    <div class="row">
        <?php
        foreach ($response as $pokemon):
            ?>
            <div class="col-sm-4">
                <div class="card card-<?= $pokemon['types'][0] ?>" style="width: 18rem;">
                    <img class="picture-overview" src="<?= $pokemon['picture'] ?> " class="card-img-top"
                         alt="<?= $pokemon['name'] ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= $pokemon['name'] ?></h5>
                        <?php foreach ($pokemon['types'] as $type): ?>
                            <span class="badge badge-pill badge-<?= $type ?>"><?= $type ?></span>
                        <?php endforeach; ?>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of
                            the
                            card's content.</p>
                        <a href="#" class="btn btn-primary">Go somewhere</a>
                    </div>
                </div>
            </div>


        <?php endforeach ?>
    </div>
    <?php $form = ActiveForm::begin() ?>

    <?php ActiveForm::end() ?>
</div>

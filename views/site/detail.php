<?php
/** @var $pokemon */

?>

<div>
    <div class="picture-overview-detail">
        <img class="picture-overview-detail" src="<?= $pokemon['picture'] ?> "
             alt="<?= $pokemon['name'] ?>">
    </div>
    <div class="row">
        <div class="col-sm-6 mx-auto justify-content-center">
            <table class="">
                <thead>
                <tr>
                    <th>Types</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($pokemon['types'] as $type): ?>
                    <tr>
                        <td><span class="badge badge-pill badge-<?= $type ?>"><?= $type ?></span></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="col-sm-6 mx-auto justify-content-center">
            <table class="">
                <thead>
                <tr>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>height: <?= $pokemon['height'] ?> m</td>
                </tr>
                <tr>
                    <td>weight: <?= $pokemon['weight'] ?> kg</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

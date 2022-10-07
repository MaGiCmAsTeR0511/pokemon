<?php
/** @var $pokemon /app/models/Pokemon */

/**
 * Counter for the quantity of Moves in one tr
 */
$counter = 0;
?>

<div>
    <div class="picture-overview-detail">
        <img class="picture-overview-detail" src="<?= $pokemon->picture ?> "
             alt="<?= $pokemon->name ?>">
    </div>

    <div class="row">
        <div class="col-sm-2 mx-auto justify-content-center">
            <table class="">
                <thead>
                <tr>
                    <th><u>Types</u></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($pokemon->types as $type): ?>
                    <tr>
                        <td><span class="badge badge-pill badge-<?= $type ?>"><?= $type ?></span></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="col-sm-6 mx-auto justify-content-center" style="width: 100px">
            <button class="info_btn" id="info_btn" onclick="show_table()">see more infos</button>
        </div>
    </div>
</div>
<div class="row">
    <div id="table_div" style="display: none">
        <table class="pokemon_data">
            <thead>
            <tr>
                <th><u>Specifications</u></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td><b>height:</b> <?= $pokemon->height ?> <b>m</b></td>
                <td><b>weight:</b> <?= $pokemon->weight ?> <b>kg</b></td>
            </tr>

            </tbody>
        </table>
        <table class="pokemon_moves">
            <thead>
                <th><u>Moves</u></th>
            </thead>
                <?php
                foreach ($pokemon->moves as $move):
                if ($counter % 4 == 0 ||$counter == 0 ): ?>
            <tr>
            <?php
            endif; ?>
            <td><?= $move ?></td>
            <?php $counter++;?>
               <?php if ($counter % 4 == 0): ?>
            </tr>
            <?php endif;
            endforeach; ?>
        </table>

    </div>
</div>
<div class="row">
    <tbody>
    <tr class="table_moves">


    </tr>

    </tbody>

</div>
<button class="info_btn hide_info_btn" id="hide_info_btn" style="display: none" onclick="hide_table()">hide infos
</button>

</div>

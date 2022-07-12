<?php
/** @var $pokemon /app/models/Pokemon */

?>

<div>
    <div class="picture-overview-detail">
        <img class="picture-overview-detail" src="<?= $pokemon->picture ?> "
             alt="<?= $pokemon->name ?>">
    </div>
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
    <div class="row" style="background-image: linear-gradient(45deg,<?php echo implode(',', $class) ;?> <?php if(count($class) < 2) : echo ", white"; endif?>); padding: 2% 0% 3% 0%">
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
                <?php endforeach;?>
                </tbody>
            </table>
        </div>
        <div class="col-sm-6 mx-auto justify-content-center" style="width: 100px">
            <button class="info_btn" id="info_btn" onclick="show_table()">see more infos</button>
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
                    </tr>
                    <tr>
                        <td><b>weight:</b> <?= $pokemon->weight ?> <b>kg</b></td>
                    </tr>
                    <tr>
                        <td><b>moves:</b></td>
                    </tr>
                    <tr class="table_moves">
                        <?php $counter = 0; foreach($pokemon->moves as $move):
                        if($counter > 4):?>
                        <tr class="table_moves"></tr>
                        <?php $counter = 0;
                        endif; ?>
                        <td class="table_moves"><?=$move?></td>
                        <?php $counter++; endforeach; ?>

                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <tbody>
                <tr class="table_moves">


                </tr>

            </tbody>

        </div>
        <button class="info_btn hide_info_btn" id="hide_info_btn" style="display: none" onclick="hide_table()">hide infos</button>

        <?php ?>


</div>

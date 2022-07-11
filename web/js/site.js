$('document').ready(function () {
    $('.pokecard').on('click', function () {
        var id = $(this).data('id');
        var name = $(this).data('name')
        $.ajax({
            url: 'site/details/' + id,
            beforeSend: function () {
                $('#pokemondetailsmodal').modal('show');
                $('.modal-body').html('<div style="display: flex; justify-content: center"><div class="pokemon"> </div></div>');
            },
            success: function (response) {
                $('.modal-body').html();
                $('#pokemondetailsmodal-label').html(name)
                $('.modal-body').html(response);
            }
        });
    })

    $('li.page-item').on('click', function () {
        $('div.row').html('<div style="display: flex; justify-content: center; width: 100%"><div class="pokemon"> </div></div>');
    })

    $('#w0').submit(function () {
        $('div.row').html('<div style="display: flex; justify-content: center; width: 100%"><div class="pokemon"> </div></div>');
    })

})

function show_table()
{
    document.getElementById("table_div").style.display = "flex";
    document.getElementById("info_btn").style.display = "none";
    document.getElementById("compare_btn").style.display = "flex";
    document.getElementById("hide_info_btn").style.display = "flex";
}

function hide_table()
{
    document.getElementById("table_div").style.display = "none";
    document.getElementById("info_btn").style.display = "flex";
    document.getElementById("compare_btn").style.display = "none";
    document.getElementById("hide_info_btn").style.display = "none";
}

function compare_pokemon()
{

}

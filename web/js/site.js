$('document').ready(function () {
    $('.pokecard').on('click', function () {
        var id = $(this).data('id');
        var name = $(this).data('name')
        $.ajax({
            url: 'site/details/' + id,
            beforeSend: function(){
                SimpleLoading.start('default')
            },
            success: function (response) {
                SimpleLoading.stop();
                $('#pokemondetailsmodal').modal('show');
                $('#pokemondetailsmodal-label').html(name)
                $('.modal-body').html(response);
            }
        });
    })

    $('li.page-item').on('click', function(){
        SimpleLoading.start('default')
    })

})
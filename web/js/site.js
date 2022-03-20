$('document').ready(function () {
    $('.pokecard').on('click', function () {
        var id = $(this).data('id');
        var name = $(this).data('name')
        $.ajax({
            url: 'site/details/' + id, success: function (response) {
                $('#pokemondetailsmodal').modal('show');
                $('#pokemondetailsmodal-label').html(name)
                $('.modal-body').html(response);
            }
        });
    })

})
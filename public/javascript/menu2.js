var $matiere = $('.block_matiere');

$matiere.on('click', function() {
    $(this).next().toggle();
});
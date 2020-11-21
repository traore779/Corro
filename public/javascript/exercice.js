var $chap = $('.chapiter-name');

$chap.on('click', function() {
    $(this).next().toggle();
});

/*window.on('load', function() {
    var $w = $('.btn p').width() + $('.btn i').width();
    $('btn').css('width', $w);
});*/
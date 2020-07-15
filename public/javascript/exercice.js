var $sol = $('.sol');

$sol.on('click', function() {
    $(this).next().toggle();
    var $icon = $('i', this);

    if ($icon.text() === 'chevron_right') {
        $icon.text('expand_more');
    } else {
        $icon.text('chevron_right');
    }
});
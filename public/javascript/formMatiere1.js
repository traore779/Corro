var $collectionHolder;
var $addChapButton = $('.add_chap_link');

jQuery(document).ready(function() {
    // Get the ul that holds the collection of chapitres
    $collectionHolder = $('ul.chapitres');

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find('input').length);

    $addChapButton.on('click', function(e) {
        var prototype = $collectionHolder.data('prototype');
        var index = $collectionHolder.data('index');

        var newForm = prototype;

        newForm = newForm.replace(/__name__/g, index);

        // increase the index with one for the next item
        $collectionHolder.data('index', index + 1);

        // Le boutton suppression de chap form
        var $removeFormButton = $('<button type="button">Supprimer ce chapitre</button>');

        /*// Ajout des exercices au chap
        var $addExercise = $('<ul class="exercices" data-prototype="{{ form_widget(formMatiere.chapitres.vars.prototype)|e(\'html_attr\') }}"></ul>');

        // Formulaire d'ajout de fichier
        var $addFile = $('<div class=""><label for="files">Ajouter des exercices au chapitre</label><input type="file" name="files" id="files"></div>');*/

        var $newFormLi = $('<li></li>').append(newForm)
            .append($removeFormButton);

        $collectionHolder.append($newFormLi);

        $($removeFormButton).on('click', function(e) {
            // remove the li for the chap form
            $(this).parent().remove();
        });
    });
});
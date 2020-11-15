const $ = require('jquery');

//SEE: https://symfony.com/doc/current/form/form_collections.html

$(document).ready(function() {
    let body = $('body');
    body.on('click', '.add_item_link', function(e) {
        e.preventDefault();

        let $wrapper = $(this).closest('.collection_wrapper');
        let prototype = $wrapper.data('prototype');
        let index = $wrapper.data('index');
        let newForm = prototype.replace(/__name__/g, index);
        $wrapper.data('index', index + 1);
        $(this).before(newForm);
    });

    body.on('click', '.remove_item_link', function(e) {
        e.preventDefault();

        let $wrapper = $(this).closest('.collection_wrapper');
        let index = $wrapper.data('index');
        $wrapper.data('index', index - 1);
        $(this).closest('table').remove();
    });
});
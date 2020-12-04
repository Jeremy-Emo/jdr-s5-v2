const $ = require('jquery');

import toastr from 'toastr';

$(document).ready(function () {
    let body = $('body');

    body.on('click', '.btn-show-hero', function (){
        $('.hero_box_to_show').hide();
        $('#hero-' + $(this).data('target')).show();
    });
});
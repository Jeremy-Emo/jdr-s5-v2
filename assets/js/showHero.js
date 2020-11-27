const $ = require('jquery');

import toastr from 'toastr';

$(document).ready(function () {
    let body = $('body');

    body.on('click', '.buy-stat', function () {
        let $this = $(this);
        let statPoints = $("#statPoints");
        if (parseInt(statPoints.text()) < 1) {
            popErrorMessage("Nombre de points insuffisants !");
        } else {
            $.post("/heros/augmenter-statistique", {
                'statId': $this.data('statid')
            }).fail(function (data) {
                popErrorMessage(data.responseJSON.message);
            }).done(function (data) {
                let statBox = $this.closest('.stat-box')
                let levelBox = statBox.find('.stat-value');
                levelBox.text(parseInt(levelBox.text()) + 1);

                statPoints.text(parseInt(statPoints.text()) - 1);
            });
        }
    });



    function popErrorMessage(message){
        let positionClass = "toast-top-left";
        if(screen.width <= 768){
            positionClass = "toast-bottom-full-width";
        }
        toastr.options = {
            "positionClass": positionClass
        }
        toastr.error(message);
    }

});
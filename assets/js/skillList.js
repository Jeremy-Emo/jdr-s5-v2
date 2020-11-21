const $ = require('jquery');

import toastr from 'toastr';

$(document).ready(function () {
    let body = $('body');

    body.on('click', '.buy-skill', function () {
        let $this = $(this);
        let skillPoints = $("#skillPoints");
        if (parseInt(skillPoints.text()) < $this.data('skillcost')) {
            popErrorMessage("Nombre de points insuffisants !");
        } else {
            $.post("/heros/acheter-competence", {
                'heroId' : $this.data('heroid'),
                'skillId': $this.data('skillid')
            }).fail(function (data) {
                popErrorMessage(data.message);
            }).done(function (data) {
                let levelBox = $this.closest('.skill_box').find('.level_box');
                levelBox.text(parseInt(levelBox.text()) + 1);
                skillPoints.text(parseInt(skillPoints.text()) - $this.data('skillcost'))
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
const $ = require('jquery');

import toastr from 'toastr';

$(document).ready(function () {
    let body = $('body');

    let antiSpam = false;

    body.on('click', '.toggleInvocation', function () {
        let $this = $(this);
        if (!antiSpam) {
            antiSpam = true;
            $.post(
                "/familier/" + $this.data('id') + "/changer-invocation"
            ).fail(function (data) {
                popErrorMessage(data.responseJSON.message);
                antiSpam = false;
            }).done(function (data) {
                if ($this.hasClass('grey')) {
                    $this.addClass('green').removeClass('grey');
                    $this.text("Utilisé");
                } else {
                    $this.addClass('grey').removeClass('green');
                    $this.text("Non-utilisé");
                }

                $("#usedLS").text(data.data.usedLeadership);

                antiSpam = false;
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
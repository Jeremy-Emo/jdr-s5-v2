const $ = require('jquery');

import toastr from 'toastr';

$(document).ready(function () {
    let body = $('body');

    let antiSpam = false;

    body.on('click', '.buy-skill', function () {
        let $this = $(this);
        let skillPoints = $("#skillPoints");
        if (parseInt(skillPoints.text()) < $this.data('skillcost')) {
            popErrorMessage("Nombre de points insuffisants !");
        } else {
            if (!antiSpam) {
                antiSpam = true;
                $.post("/heros/acheter-competence", {
                    'heroId' : $this.data('heroid'),
                    'skillId': $this.data('skillid')
                }).fail(function (data) {
                    popErrorMessage(data.responseJSON.message);
                    antiSpam = false;
                }).done(function (data) {
                    let skillBox = $this.closest('.skill_box')
                    let levelBox = skillBox.find('.level_box');
                    levelBox.text(parseInt(levelBox.text()) + 1);
                    skillPoints.text(parseInt(skillPoints.text()) - $this.data('skillcost'));
                    if (!skillBox.hasClass("heroSkill")) {
                        skillBox.addClass("heroSkill");
                    }
                    if (data.data.unlockedSkills.length > 0) {
                        localStorage.setItem('unlockedSkills', JSON.stringify(data.data.unlockedSkills));
                        location.reload()
                    }
                    antiSpam = false;
                });
            }
        }
    });

    body.on('click', '#randomSkill', function () {
        let $this = $(this);
        let skillPoints = $("#skillPoints");
        if (parseInt(skillPoints.text()) < 1) {
            popErrorMessage("Nombre de points insuffisants !");
        } else {
            if (!antiSpam) {
                antiSpam = true;
                $.post("/heros/acheter-competence-aleatoire", {
                    'heroId': $this.data('heroid'),
                }).fail(function (data) {
                    popErrorMessage(data.responseJSON.message);
                    antiSpam = false;
                }).done(function (data) {
                    let skillBox = $('[data-idforrandom=' + data.data.id + ']');
                    let levelBox = skillBox.find('.level_box');
                    levelBox.text(parseInt(levelBox.text()) + 1);
                    skillPoints.text(parseInt(skillPoints.text()) - data.data.cost);
                    if (!skillBox.hasClass("heroSkill")) {
                        skillBox.addClass("heroSkill");
                    }
                    if (data.data.unlockedSkills.length > 0) {
                        localStorage.setItem('unlockedSkills', JSON.stringify(data.data.unlockedSkills));
                        location.reload()
                    }
                    antiSpam = false;
                });
            }
        }
    });

    body.on('click', '#toggleSkills', function () {
        let $this = $(this);
        if ($this.data('state') === "all") {
            $this.text("Afficher toutes les compétences");
            $('.skill_box').each(function(index) {
                if (!$(this).hasClass('heroSkill')) {
                    $(this).hide();
                }
            });
            $this.data('state', 'my');
        } else {
            $this.text("Afficher mes compétences");
            $('.skill_box').each(function(index) {
                $(this).show();
            });
            $this.data('state', 'all');
        }
    });

    body.on('change input', "#searchBar", function(){
        let search = $("#searchBar").val();
        let allRows = body.find(".searchedItem");
        allRows.each(function(index, row) {
            $(row).show();
        });
        if (search !== "") {
            let rows = $(".searchedItem").not("[data-search*='" + search.toLowerCase() + "']");
            rows.each(function(index, row) {
                $(row).hide();
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

    if (localStorage.getItem('unlockedSkills')) {
        let text = "<span class='bold'>Compétences débloquées : </span><br>";
        JSON.parse(localStorage.getItem('unlockedSkills')).forEach(function (index) {
            text += " - " + index + "<br>";
        });
        $("#unlockedSkills").html(text);
        localStorage.removeItem('unlockedSkills');
    }

});
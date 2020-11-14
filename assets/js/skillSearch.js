const $ = require('jquery');

$(document).ready(function(){
    $("body").on('change input', "#searchSkillBar", function(){
        let search = $("#searchSkillBar").val();
        resetSkillsTable();
        if (search !== "") {
            let rows = $(".searchedItem").not("[data-search*='" + search + "']");
            rows.each(function(index, row) {
                $(row).hide();
            });
        }
    });

    function resetSkillsTable(){
        let allRows = $("#skillsTable").find(".searchedItem");
        allRows.each(function(index, row) {
            $(row).show();
        });
    }
});
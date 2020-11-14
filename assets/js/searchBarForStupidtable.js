const $ = require('jquery');

$(document).ready(function(){
    $("body").on('change input', "#searchBar", function(){
        let search = $("#searchBar").val();
        resetSkillsTable();
        if (search !== "") {
            let rows = $(".searchedItem").not("[data-search*='" + search.toLowerCase() + "']");
            rows.each(function(index, row) {
                $(row).hide();
            });
        }
    });

    function resetSkillsTable(){
        let allRows = $("#stupidTable").find(".searchedItem");
        allRows.each(function(index, row) {
            $(row).show();
        });
    }
});
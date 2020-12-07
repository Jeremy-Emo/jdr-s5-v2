const $ = require('jquery');

$(document).ready(function(){
    $("body").on('change input', "#searchSelect", function(){
        let search = $("#searchSelect option:selected").val();
        resetSkillsTable();
        if (search !== "") {
            let rows = $(".searchedItem").not("[data-search-select*='|" + search + "|']");
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
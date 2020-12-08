const $ = require('jquery');

$(document).ready(function(){
   $('form').submit(function (e) {
       $(':disabled').each(function(e) {
           $(this).removeAttr('disabled');
       })
   });
});
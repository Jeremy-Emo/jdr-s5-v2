const $ = require('jquery');

import Konami from 'konami';

$(document).ready(function () {
    let easter_egg = new Konami(function() {
        alert('Konami code!'); //remplacez par votre code
    });
});
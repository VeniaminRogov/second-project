/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (style.css in this case)

// start the Stimulus application
import 'bootstrap/dist/js/bootstrap.bundle.min';
import './js/pagination'
import './bootstrap';
import $ from 'jquery';
import Cookies from "js-cookie";

$(document).ready(function(){
    $('option[value='+Cookies.get('lang')+']').attr('selected', 'selected');
    $("select.language").change(function(){
        let lang = $(this).children("option:selected").val();
        console.log(lang)
        Cookies.set('lang', lang);
        location.reload();
    });
});



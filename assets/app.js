/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (style.css in this case)

// start the Stimulus application
import 'bootstrap/dist/js/bootstrap.bundle.min';

import './bootstrap';
import $ from 'jquery';
import Cookies from "js-cookie";


const form = document.querySelector('form');
const sortCategory = document.querySelector('#sort_category_form_sort');
const sortUsers = document.querySelector('#sort_user_form_sort');
const pageCategoryInput = document.querySelector('#sort_category_form_page');
const pageUserInput = document.querySelector('#sort_user_form_page');

document.querySelectorAll('a.page-link').forEach(link => {
    link.addEventListener('click', e => {
        e.preventDefault();
        if (pageUserInput){
            pageUserInput.value = e.target.getAttribute('href').split('').pop();
        }
        if(pageCategoryInput){
            pageCategoryInput.value = e.target.getAttribute('href').split('').pop();
        }
        form.submit();
    });
});

if(sortCategory)
{
    sortCategory.addEventListener('click', e => {
        pageCategoryInput.value = 1;
    });
}

if (sortUsers)
{
    sortUsers.addEventListener('click', e => {
        pageUserInput.value = 1;
    });
}

$(document).ready(function(){
    // Cookies.remove('lang');
    $('option[value='+Cookies.get('lang')+']').attr('selected', 'selected');
    $("select.language").change(function(){
        let lang = $(this).children("option:selected").val();
        console.log(lang)
        Cookies.set('lang', lang);
        location.reload();
    });
});
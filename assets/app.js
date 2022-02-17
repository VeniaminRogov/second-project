/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (style.css in this case)

// start the Stimulus application
import './bootstrap';

require('bootstrap/dist/js/bootstrap.bundle.min');

const form = document.querySelector('form');
const sort = document.querySelector('#sort_category_form_sort');
const pageInput = document.querySelector('#sort_category_form_page');

console.log(sort);

document.querySelectorAll('a.page-link').forEach(link => {
    link.addEventListener('click', e => {
        e.preventDefault();
        pageInput.value = e.target.getAttribute('href').split('').pop();
        form.submit();
    });
});

sort.addEventListener('click', e => {
    pageInput.value = 1;
});


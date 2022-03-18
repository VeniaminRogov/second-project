console.log('pag');
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
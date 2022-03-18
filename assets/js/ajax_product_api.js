import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min';
const routes = require('../../public/js/fos_js_routes.json');
import $ from 'jquery';

function appendProduct(value = null)
{
    $('.table').append($(`
        <tr>
            <td>${value.id}</td>
            <td>${value.category}</td>
            <td>
               <img src="" alt="Product ${value.name} not found">
            </td>
            <td>${value.name}</td>
            <td>${value.description}</td>
            <td>${value.price}</td>
            <td>${value.quantity}</td>
            <td>${value.isAvailable}</td>
            <td>${value.createdAt.date}</td>
            <td>${value.updatedAt.date}</td>
            <td>
                <a href="${Routing.generate('manager_edit_products', {id: value.id})}" class="btn btn-primary">Edit</a>
                <a href="${Routing.generate('manager_delete_products', {id: value.id})}" class="btn btn-danger">Delete</a>
            </td>
        </tr>
    `));
}

function createProduct(img) {
    $('[name="form"]').on('submit', function (e){
        e.preventDefault();
        const object = {
            name: $('[name="name"]').val(),
            price: parseInt($('[name="price"]').val()),
            quantity: parseInt($('[name="quantity"]').val()),
            category: parseInt($('[name="category"]').val()),
            description: $('[name="description"]').val(),
            isAvailable: $('[name="isAvailable"]').val(),
            image: img
        };

        $.post(
            Routing.generate('add'),
            JSON.stringify(object),
            function (data){
                data.forEach((value, key) => appendProduct(value));

                $('[name="form"]').trigger('reset');
            }
        );
    });
}

function getProducts()
{
    $.get(
        Routing.generate('index'),
        function (data){
            data.forEach((value, key) =>
                appendProduct(value));
        }
    );
}

$(document).ready(function(){
    $('.notice').fadeToggle(250);

    Routing.setRoutingData(routes);

    getProducts();

    let imageInp = $('#image');

    imageInp.on('change', function (e) {
        e.preventDefault();

        const formData = new FormData();

        formData.append('image', imageInp.prop('files')[0])

        $.ajax({
            url: Routing.generate('create'),
            method: 'post',
            data: formData,
            contentType:false,
            processData:false,
            cache:false,
            enctype: "multipart/form-data",
            success: function(dataImg) {
                createProduct(dataImg)
            }
        })
    });
});



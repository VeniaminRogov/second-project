import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min'
const routes = require('../../public/js/fos_js_routes.json');
import $ from 'jquery';

function appendProduct(value = null)
{
    // console.log(imgName)
    $('.products-table').append($(`
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

function createProduct() {
    $('[name="product_form"]').on('submit', function (e){
        e.preventDefault();
        const object = {
            name: $('[name="product_name"]').val(),
            price: parseInt($('[name="product_price"]').val()),
            quantity: parseInt($('[name="product_quantity"]').val()),
            category: parseInt($('[name="product_category"]').val()),
            description: $('[name="product_description"]').val(),
            isAvailable: $('[name="product_isAvailable"]').val(),
            // image: imgName.imgName
        };

        $.post(
            Routing.generate('product_add'),
            JSON.stringify(object),
            function (data){
                data.forEach((value, key) => appendProduct(value));

                $('[name="product_form"]').trigger('reset');
            }
        );
    });
}

function getProducts()
{
    $.get(
        Routing.generate('product_index'),
        function (data){
            data.forEach((value, key) =>
                appendProduct(value));
        }
    );
}

function appendService(value = null)
{
    // console.log(imgName)
    $('.service-table').append($(`
        <tr>
            <td>${value.id}</td>
            <td>${value.category}</td>
            <td>
               <img src="" alt="Service ${value.name} not found">
            </td>
            <td>${value.name}</td>
            <td>${value.description}</td>
            <td>${value.price}</td>
            <td>${value.time}</td>
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

function createService() {
    $('[name="service_form"]').on('submit', function (e){
        e.preventDefault();
        const object = {
            name: $('[name="service_name"]').val(),
            price: parseInt($('[name="service_price"]').val()),
            time: parseInt($('[name="service_time"]').val()),
            category: parseInt($('[name="service_category"]').val()),
            description: $('[name="service_description"]').val(),
            isAvailable: $('[name="service_isAvailable"]').val(),
            // image: imgName.imgName
        };

        $.post(
            Routing.generate('service_add'),
            JSON.stringify(object),
            function (data){
                data.forEach((value, key) => appendService(value));

                $('[name="service_form"]').trigger('reset');
            }
        );
    });
}

function getServices()
{
    $.get(
        Routing.generate('service_index'),
        function (data){
            data.forEach((value, key) =>
                appendService(value));
        }
    );
}

$(document).ready(function(){
    $('.notice').fadeToggle(250);

    Routing.setRoutingData(routes);

    getProducts();
    createProduct();

    getServices();
    createService()

    // let imageInp = $('#image');
    //
    // imageInp.on('change', function (e) {
    //     e.preventDefault();
    //
    //     const formData = new FormData();
    //
    //     formData.append('image', imageInp.prop('files')[0])
    //
    //     $.ajax({
    //         url: Routing.generate('create'),
    //         method: 'post',
    //         data: formData,
    //         contentType:false,
    //         processData:false,
    //         cache:false,
    //         enctype: "multipart/form-data",
    //         success: function(imgName) {
    //             createProduct(imgName)
    //         }
    //     })

});

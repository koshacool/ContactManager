;
requirejs.config({
    'baseUrl': '/public/js/app',
    'paths': {
        'jquery': '../lib/jquery-3.1.1'
    },

    'shim': {
        'jquery': {
         exports: 'jquery'
        }
    },

});


requirejs(['jquery','helper', 'validation'], function ($, helper, validation) {
    validation.setEvents();

    var pages = {
        'record': 'select',
        'select': 'pagination',
        'showlist': 'pagination',
    };

    //Connect these modules only in pages, which names is in variable 'pages'
    for (var key in pages) {
        if (helper.getUrl().indexOf(key) + +1) {
            requirejs(['PaginationSorting', 'Select'], function (pagination, select) {
                pagination.setEvents();
                select.setEvents();
            });
        }
    }

});
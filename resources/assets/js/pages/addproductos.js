"use strict";
// bootstrap wizard//



$("#productosForm").bootstrapValidator({
    fields: {
        nombre_producto: {
            validators: {
                notEmpty: {
                    message: 'Nombre de Producto es Requerido'
                }
            },
            required: true,
            minlength: 3
        },
        referencia_producto: {
            validators: {
                notEmpty: {
                    message: 'The referencia_producto name is required'
                }
            },
            required: true,
            minlength: 3
        },
        referencia_producto_sap: {
            validators: {
                notEmpty: {
                    message: 'The referencia_producto_sap name is required'
                }
            },
            required: true,
            minlength: 3
        },
        descripcion_corta: {
            validators: {
                notEmpty: {
                    message: 'descripcion_corta is required and cannot be empty'
                }
            },
            minlength: 20
        },
        descripcion_larga: {
            validators: {
                notEmpty: {
                    message: 'descripcion_corta is required and cannot be empty'
                }
            },
            minlength: 20
        },
        seo_titulo: {
            validators: {
                notEmpty: {
                    message: 'The seo_titulo name is required'
                }
            },
            required: true,
            minlength: 3
        },
        seo_descripcion: {
            validators: {
                notEmpty: {
                    message: 'The seo_descripcion name is required'
                }
            },
            required: true,
            minlength: 3
        },
        seo_url: {
            validators: {
                notEmpty: {
                    message: 'The seo_url name is required'
                }
            },
            required: true,
            minlength: 3
        },

        id_categoria_default: {
            validators: {
                notEmpty: {
                    message: 'Please select a id_categoria_default'
                }
            }
        },

        id_marca: {
            validators:{
                notEmpty:{
                    message: 'You must select a id_marca'
                }
            }
        }
    }
});



$('.finish').click(function () {
    var $validator = $('#productosForm').data('bootstrapValidator').validate();
    if ($validator.isValid()) {
        document.getElementById("productosForm").submit();
    }

});
// $('#activate').on('ifChanged', function(event){
//     $('#commentForm').bootstrapValidator('revalidateField', $('#activate'));
// });
$('#productosForm').keypress(
    function(event){
        if (event.which == '13') {
            event.preventDefault();
        }
    });
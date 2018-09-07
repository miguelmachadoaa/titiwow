"use strict";
// bootstrap wizard//
$("#genero_cliente, #genero_cliente1").select2({
    theme:"bootstrap",
    placeholder:"",
    width: '100%'
});
$("#id_type_doc, #id_type_doc1").select2({
    theme:"bootstrap",
    placeholder:"",
    width: '100%'
});
$('input[type="checkbox"].custom-checkbox, input[type="radio"].custom-radio').iCheck({
    checkboxClass: 'icheckbox_minimal-blue',
    radioClass: 'iradio_minimal-blue',
    increaseArea: '20%'
});
$("#dob").datetimepicker({
    format: 'YYYY-MM-DD',
    widgetPositioning:{
        vertical:'bottom'
    },
    keepOpen:false,
    useCurrent: false,
    maxDate: moment().add(1,'h').toDate(),
    locale: 'es',
});
$("#commentForm").bootstrapValidator({
    fields: {
        first_name: {
            validators: {
                notEmpty: {
                    message: 'El Nombre es Requerido'
                }
            },
            required: true,
            minlength: 3
        },
        last_name: {
            validators: {
                notEmpty: {
                    message: 'El Apellido es Requerido'
                }
            },
            required: true,
            minlength: 3
        },
        password: {
            validators: {
                notEmpty: {
                    message: 'La Contraseña es Requerida'
                },
                different: {
                    field: 'first_name,last_name',
                    message: 'La clave no debe ser igual al nombre'
                }
            }
        },
        password_confirm: {
            validators: {
                notEmpty: {
                    message: 'La Confirmación de la contraseña es requerida'
                },
                identical: {
                    field: 'password'
                },
                different: {
                    field: 'first_name,last_name',
                    message: 'La Contraseña debe coincidir'
                }
            }
        },
        email: {
            validators: {
                notEmpty: {
                    message: 'El Email es Requerido'
                },
                emailAddress: {
                    message: 'Debe introducir un Email Válido'
                }
            }
        },
        group: {
            validators:{
                notEmpty:{
                    message: 'Debe seleccionar un grupo para este Usuario'
                }
            }
        },        
        id_type_doc: {
            validators:{
                notEmpty:{
                    message: 'Debe seleccionar un Tipo de Documento'
                }
            }
        },
        doc_cliente: {
            validators:{
                notEmpty:{
                    message: 'El Número de Documento es Requerido'
                },
                required: true,
                minlength: 3
            }
        },
        telefono_cliente: {
            validators:{
                notEmpty:{
                    message: 'El Número Telefónico es Requerido'
                },
                required: true,
                minlength: 3
            }
        },
    }
});

$('#rootwizard').bootstrapWizard({
    'tabClass': 'nav nav-pills',
    'onNext': function(tab, navigation, index) {
        var $validator = $('#commentForm').data('bootstrapValidator').validate();
        return $validator.isValid();
    },
    onTabClick: function(tab, navigation, index) {
        return false;
    },
    onTabShow: function(tab, navigation, index) {
        var $total = navigation.find('li').length;
        var $current = index + 1;

        // If it's the last tab then hide the last button and show the finish instead
        if ($current >= $total) {
            $('#rootwizard').find('.pager .next').hide();
            $('#rootwizard').find('.pager .finish').show();
            $('#rootwizard').find('.pager .finish').removeClass('disabled');
        } else {
            $('#rootwizard').find('.pager .next').show();
            $('#rootwizard').find('.pager .finish').hide();
        }
    }});

$('#rootwizard .finish').click(function () {
    var $validator = $('#commentForm').data('bootstrapValidator').validate();
    if ($validator.isValid()) {
        document.getElementById("commentForm").submit();
    }

});
// $('#activate').on('ifChanged', function(event){
//     $('#commentForm').bootstrapValidator('revalidateField', $('#activate'));
// });
$('#commentForm').keypress(
    function(event){
        if (event.which == '13') {
            event.preventDefault();
        }
    });
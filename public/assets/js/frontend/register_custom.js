$(document).ready(function(){
    /*$("input[type='checkbox'],input[type='radio']").iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass: 'iradio_minimal-blue'
    });*/

$("#reg_form").bootstrapValidator({
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
        id_type_doc: {
            validators: {
                notEmpty: {
                    message: 'Debe Indicar el Tipo de Documento'
                }
            },
            required: true
        },
        doc_cliente: {
            validators: {
                notEmpty: {
                    message: 'El Número de Documento Requerido'
                }
            },
            required: true,
            minlength: 3
            
        },
        telefono_cliente: {
            validators: {
                notEmpty: {
                    message: 'El Número de Teléfono Requerido'
                }
            },
            required: true,
            minlength: 3
        },
        email: {
            validators: {
                notEmpty: {
                    message: 'El Email es Requerido'
                },
                regexp: {
                    regexp: /^(\w+)([\-+.\'0-9A-Za-z_]+)*@(\w[\-\w]*\.){1,5}([A-Za-z]){2,6}$/,
                    message: 'El Email no es Válido'
                }
            }
        },
        password: {
            validators: {
                notEmpty: {
                    message: 'La Contraseña es Requerida'
                },
                different: {
                    field: 'first_name,last_name',
                    message: 'La Contraseña debe ser Distinta al Nombre/Apellido'
                }
            }
        },
        password_confirm: {
            validators: {
                notEmpty: {
                    message: 'Debe Confirmar la Contraseña'
                },
                identical: {
                    field: 'password'
                },
                different: {
                    field: 'first_name,last_name',
                    message: 'La Contraseña debe ser Distinta al Nombre/Apellido'
                }
            }
        },
        terminos_cliente: {
            validators: {
                notEmpty: {
                    message: 'Debe Aceptar Términos y Condiciones'
                }
            },
            required: true,
            minlength: 3
        },
        habeas_cliente: {
            validators: {
                notEmpty: {
                    message: 'Debe la Aceptar Política de Tratamiento de Datos'
                }
            },
            required: true,
            minlength: 3
        }
    }
});
});

/*$('#reg_form input').on('keyup', function (){

    $('#reg_form input').each(function(){
        var pswd = $("#reg_form input[name='password']").val();
        var pswd_cnf = $("#reg_form input[name='password_confirm']").val();
            if(pswd != '' ){
                $('#reg_form').bootstrapValidator('revalidateField', 'password');
            }
            if(pswd_cnf != '' ){
                $('#reg_form').bootstrapValidator('revalidateField', 'password_confirm');
            }
    });
});*/
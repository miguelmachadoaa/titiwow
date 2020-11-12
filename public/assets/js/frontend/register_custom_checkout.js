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
        
        state_id: {
            validators: {
                notEmpty: {
                    message: 'Debe Indicar el Departamento'
                }
            },
            required: true
        },
        city_id: {
            validators: {
                notEmpty: {
                    message: 'Debe Indicar La Ciudad'
                }
            },
            required: true
        },
        id_estructura_address: {
            validators: {
                notEmpty: {
                    message: 'Debe Indicar La Dirección'
                }
            },
            required: true
        },
        principal_address: {
            validators: {
                notEmpty: {
                    message: 'Dirección Principal es Requerida'
                }
            },
            required: true,
            minlength: 1
        },
        secundaria_address: {
            validators: {
                notEmpty: {
                    message: 'Dirección Secundaria es Requerida'
                }
            },
            required: true,
            minlength: 1
        },
        edificio_address: {
            validators: {
                notEmpty: {
                    message: 'El Número del Edificio es Requerido'
                }
            },
            required: true,
            minlength: 3
        },
        id_barrio: {
            validators: {
                notEmpty: {
                    message: 'Debe Indicar El Barrio'
                }
            },
            required: true
        },
        barrio_address: {
            validators: {
                notEmpty: {
                    message: 'El Barrio es Requerido'
                }
            },
            required: true,
            minlength: 3
        },
        habeas_cliente: {
            validators: {
                notEmpty: {
                    message: 'Debe Aceptar Términos y Condiciones'
                }
            },
            required: true,
            minlength: 3
        }
    }
});




$("#dir_form").bootstrapValidator({
    fields: {
        
        
        state_id_dir: {
            validators: {
                notEmpty: {
                    message: 'Debe Indicar el Departamento'
                }
            },
            required: true
        },
        city_id_dir: {
            validators: {
                notEmpty: {
                    message: 'Debe Indicar La Ciudad'
                }
            },
            required: true
        },
        id_estructura_address_dir: {
            validators: {
                notEmpty: {
                    message: 'Debe Indicar La Dirección'
                }
            },
            required: true
        },
        principal_address_dir: {
            validators: {
                notEmpty: {
                    message: 'Dirección Principal es Requerida'
                }
            },
            required: true,
            minlength: 1
        },
        secundaria_address_dir: {
            validators: {
                notEmpty: {
                    message: 'Dirección Secundaria es Requerida'
                }
            },
            required: true,
            minlength: 1
        },
        edificio_address_dir: {
            validators: {
                notEmpty: {
                    message: 'El Número del Edificio es Requerido'
                }
            },
            required: true,
            minlength: 3
        },
        id_barrio_dir: {
            validators: {
                notEmpty: {
                    message: 'Debe Indicar El Barrio'
                }
            },
            required: true
        },
        barrio_address_dir: {
            validators: {
                notEmpty: {
                    message: 'El Barrio es Requerido'
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
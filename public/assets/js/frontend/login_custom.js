$(document).ready(function () {
    $("input[type='checkbox']").iCheck({
        checkboxClass: 'icheckbox_minimal-blue'
    });


    $(".omb_loginForm").bootstrapValidator({
        fields: {
            email: {
                validators: {
                    notEmpty: {
                        message: 'El Email es Requerido'
                    },
                    emailAddress: {
                        message: 'Debe ingresar un Email válido'
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
                        message: 'Password should not match user Name'
                    }
                }
            }
        }
    });
});
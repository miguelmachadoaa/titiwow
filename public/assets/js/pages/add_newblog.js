$(document).ready(function() {
    
    $('.form-group input[type=file]').attr("accept","image/*");

    // CKEditor Standard
    $('textarea#texto_pagina').ckeditor({
        height: '150px',
        toolbar: [{
            name: 'document',
            items: ['Source', '-', 'NewPage', 'Preview', '-', 'Templates']
        }, // Defines toolbar group with name (used to create voice label) and items in 3 subgroups.
            ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'], // Defines toolbar group without name.
            {
                name: 'basicstyles',
                items: ['Bold', 'Italic']
            }
        ]
    });
    $('body').on('click', '.btn-codeview', function (e) {

        if ( $('.note-editor').hasClass("fullscreen") ) {
            var windowHeight = $(window).height();
            $('.note-editable').css('min-height',windowHeight);
        }else{
            $('.note-editable').css('min-height','300px');
        }
    });
    $('body').on('click','.btn-fullscreen', function (e) {
        setTimeout (function(){
            if ( $('.note-editor').hasClass("fullscreen") ) {
                var windowHeight = $(window).height();
                $('.note-editable').css('min-height',windowHeight);
            }else{
                $('.note-editable').css('min-height','300px');
            }
        },500);

    });

    $('.note-link-url').on('keyup', function() {
        if($('.note-link-text').val() != '') {
            $('.note-link-btn').attr('disabled', false).removeClass('disabled');
        }
    });
});
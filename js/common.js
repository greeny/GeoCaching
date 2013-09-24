$(document).ready(function(){
    $(".alert").alert();
    $("[data-tooltip]").tooltip();

    $resizable = $(".resizable");
    $resizable.resizable({
        alsoResize: '.resizable .resizable-iframe',
    });/*
    $resizable.resizable({
        resize: function(event, ui) {
            $(this).child('.resizable-iframe').css({ "height": ui.size.height,"width":ui.size.width});
        }
    });*/
});
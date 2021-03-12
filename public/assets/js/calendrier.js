$('#md-trigger').on('click', function(e) {
    $('#modal-1').toggleClass("md-show"); //you can list several class names
    e.preventDefault();
});

$('#md-close').on('click', function(e) {
    $('#modal-1').toggleClass("md-show"); //you can list several class names
    e.preventDefault();
});
$('button[data-modal]').click(function () {
    $('.modal[data-modal="' + $(this).data('modal') + '"]').modal('show');
});

$('select.dropdown').dropdown();
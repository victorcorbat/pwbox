
/*
$('#renameFolder').on('show.bs.modal', function(e) {
    var folderid = $(e.relatedTarget).data('id');
    var form = $(e.currentTarget).find('#renameform');
    form.attr('action')=form.attr('action') + folderid;
});
*/

$(document).on("click", ".open-renameFolder", function () {
    var folderid = $(e.relatedTarget).data('id');
    document.getElementById("#renameForm").action = document.getElementById("#renameForm").action + folderid;
});



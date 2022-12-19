$(document).ready(function () {
    $('#sys_country_id').on('select2:select', function (e) {
        var data = e.params.data;
        $('#sys_state_id').html('');
    });
});


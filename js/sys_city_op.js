/* created using Dev Tools V3 */


var sys_country_id_search_select = $('.dt-input:input[name="sys_country_id"]');
var sys_country_id_select = $('#sys_country_id');

var sys_state_id_search_select = $('.dt-input:input[name="sys_state_id"]');
var sys_state_id_select = $('#sys_state_id');



$(document).ready(function () {
    select2Init(sys_country_id_search_select, route('admin.system_management.system_initialization.system_entries.sys_country.getList', { "locale": _LANGUAGE }), true, 'name');
    select2Init(sys_country_id_select, route('admin.system_management.system_initialization.system_entries.sys_country.getList', { "locale": _LANGUAGE }), false, 'name');
    select2Init(sys_state_id_search_select, route('admin.system_management.system_initialization.system_entries.sys_state.getList', { "locale": _LANGUAGE }), true, 'name');
    select2Init(sys_state_id_select, route('admin.system_management.system_initialization.system_entries.sys_state.getList', { "locale": _LANGUAGE }), true, 'name', '#sys_country_id', 'sysCountry_r');

    $("#sys_city_op_form").submit(function (e) {
        e.preventDefault();
        $("#sys_city_op_form #submit").attr('disabled', true);
        blockUi("#sys_city_op_modal");
        removeFormErrors("#sys_city_op_form");
        var result = formValidation("#sys_city_op_form");
        if (result) {
            add_sys_city();
        } else {
            blockUiRemove();
            $("#submit").attr('disabled', false);
        }
    });

    $('#sys_city_op_modal').on('shown.bs.modal', function () {
        $('#sys_city_op_form  :visible').find('input, textarea ,select').first().focus();
    });
});
function OpenSysCityAddModal() {
    sys_country_id_select.html('');
    sys_state_id_select.html('');
    $('#sys_city_op_form')[0].reset();
    removeFormErrors("#sys_city_op_form");

    $('#sys_city_op_modal_title').html(i18next.t('global.add') + i18next.t('cruds.sys_city'));
    $('.sys_city_op_form_submit').html(i18next.t('global.add'));


    appendInputToForm('#sys_city_op_form', 'sys_city_op', 'sys_city_op', '0');
    $('#sys_city_op_modal').modal({ backdrop: 'static', keyboard: false });
    $('#sys_city_op_modal').modal('show');
}

function OpenSysCityEditModal($id) {
    sys_country_id_select.html('');
    sys_state_id_select.html('');
    $('#sys_city_op_form')[0].reset();
    removeFormErrors("#sys_city_op_form");
    $('#sys_city_op_form')[0].reset();
    $("#sys_city_op_form #submit").attr('disabled', true);
    blockUi("#sys_city_op_modal");
    requestCall('GET', route('admin.system_management.system_initialization.system_entries.sys_city.edit', { "locale": _LANGUAGE, 'id': $id }), $('#sys_city_op_form').serialize())
        .then(response => {
            appendInputToForm('#sys_city_op_form', 'sys_city_id', 'sys_city_id', $id);
            appendInputToForm('#sys_city_op_form', 'sys_city_op', 'sys_city_op', '1');
            $('#sys_city_op_modal_title').html(i18next.t('global.edit') + i18next.t('cruds.sys_city'));
            $('.sys_city_op_form_submit').html(i18next.t('global.edit'));
            select2OptionsInEditMode(sys_country_id_select, response['data']['sys_country_id'], response['data']['sys_country_id_name']);
            select2OptionsInEditMode(sys_state_id_select, response['data']['sys_state_id'], response['data']['sys_state_id_name']);
            fillFormDate('#sys_city_op_modal', response);
            blockUiRemove();
            $("#sys_city_op_form #submit").attr('disabled', false);
            $('#sys_city_op_modal').modal({ backdrop: 'static', keyboard: false });
            $('#sys_city_op_modal').modal('show');
        }).catch(e => {
            blockUiRemove();
            errorHandling(e, '#sys_city_op_form');
            $("#sys_city_op_form #submit").attr('disabled', false);
        });
}

function add_sys_city() {
    var url, method, msg;
    if ($('#sys_city_op').length && $('#sys_city_op').val() == '0') {
        url = route('admin.system_management.system_initialization.system_entries.sys_city.store', { "locale": _LANGUAGE });
        method = 'POST';
        msg = i18next.t('msg.added');

    } else {
        url = route('admin.system_management.system_initialization.system_entries.sys_city.update', { "locale": _LANGUAGE, "id": $('#sys_city_id').val() });
        method = 'PUT';
        msg = i18next.t('msg.updated');
    }

    requestCall(method, url, $('#sys_city_op_form').serialize())
        .then(response => {
            $('#sys_city_op_form')[0].reset();
            blockUiRemove();
            $("#sys_city_op_form #submit").attr('disabled', false);
            $('#sys_city_op_modal').modal('hide');
            showToast('success', i18next.t('msg.operation_success'), msg, 5000);
            iniTable();
        }).catch(e => {
            blockUiRemove();
            errorHandling(e, '#sys_city_op_form');
            $("#sys_city_op_form #submit").attr('disabled', false);
        });

}


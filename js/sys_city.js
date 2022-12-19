/* created using Dev Tools V3 */

$(document).ready(function () {
    $('#btn_add_sys_city').on("click", function () {
        OpenSysCityAddModal();
    });

    if ($('.datatable-sys_city').length) {
        iniTable();
    }
});

function OpenSysCityEdit($id) {
    OpenSysCityEditModal($id);
}

function deleteSysCity($id) {

    showSweetAlart(i18next.t('msg.are_you_sure_you_want_to') + i18next.t('global.delete') + ' ' + i18next.t('cruds.sys_city_'), i18next.t('global.confirmation_operation'), 'warning',
        i18next.t('msg.yes') + i18next.t('global.delete') + ' ' + i18next.t('cruds.sys_city_'),
        async function () {
            await requestCall('DELETE', route('admin.system_management.system_initialization.system_entries.sys_city.delete', { "locale": _LANGUAGE, 'id': $id }),
                { _token: $('meta[name="csrf-token"]').attr('content') })
                .then(response => {
                    iniTable();
                    showToast('success', i18next.t('msg.operation_success'), i18next.t('msg.deleted'), 5000);
                }).catch(e => {
                    errorHandling(e);
                });
        },
    );
}

function OpenSysCityEnable($id) {

    showSweetAlart(i18next.t('msg.are_you_sure_you_want_to') + i18next.t('global.enable') + ' ' + i18next.t('cruds.sys_city_'), i18next.t('global.confirmation_operation'), 'warning',
        i18next.t('msg.yes') + i18next.t('global.enable') + ' ' + i18next.t('cruds.sys_city_'),
        async function () {
            await requestCall('POST', route('admin.system_management.system_initialization.system_entries.sys_city.enable', { "locale": _LANGUAGE, 'id': $id, 'property': 'isActive', 'value': '1' }),
                { _token: $('meta[name="csrf-token"]').attr('content') })
                .then(response => {
                    iniTable();
                    showToast('success', i18next.t('msg.operation_success'), i18next.t('msg.updated'), 5000);
                }).catch(e => {
                    errorHandling(e);
                });
        },
    );
}

function OpenSysCityDisable($id) {

    showSweetAlart(i18next.t('msg.are_you_sure_you_want_to') + i18next.t('global.disable') + ' ' + i18next.t('cruds.sys_city_'), i18next.t('global.confirmation_operation'), 'warning',
        i18next.t('msg.yes') + i18next.t('global.disable') + ' ' + i18next.t('cruds.sys_city_'),
        async function () {
            await requestCall('POST', route('admin.system_management.system_initialization.system_entries.sys_city.disable', { "locale": _LANGUAGE, 'id': $id, 'property': 'isActive', 'value': '0' }),
                { _token: $('meta[name="csrf-token"]').attr('content') })
                .then(response => {
                    iniTable();
                    showToast('success', i18next.t('msg.operation_success'), i18next.t('msg.updated'), 5000);
                }).catch(e => {
                    errorHandling(e);
                });
        },
    );
}

function iniTable() {
    blockUi(".card");
    if (!$.fn.dataTable.isDataTable('.datatable-sys_city')) {
        var dt_basic = $('.datatable-sys_city').DataTable({
            ajax: {
                url: route('admin.system_management.system_initialization.system_entries.sys_city.sysCityGet', { "locale": _LANGUAGE }),
                type: 'GET',
                data: function (d) {
                    var inputs = $('.dt_adv_search .dt-input');
                    var dt = [];
                    $.each(inputs, function (index, value) {
                        if ($(value).val() !== '')
                            dt[$(value).attr('name')] = $(value).val();
                    });
                    d['column_search'] = JSON.stringify(Object.assign({}, dt));
                },
            },
            processing: true,
            serverSide: true,
            searching: false,
            columns: [
                { data: 'responsive_id' },
                { data: 'id' },
                { data: 'Actions' },
                { data: 'id' },
                { data: 'name' },
                { data: 'sys_country_id' },
                { data: 'sys_state_id' },
                { data: 'country_code' },
                { data: 'state_code' },
                { data: 'latitude' },
                { data: 'longitude' },
                { data: 'created_at' },
                { data: 'updated_at' },
            ],
            columnDefs: [
                {
                    // For Responsive
                    className: 'control',
                    orderable: false,
                    responsivePriority: 2,
                    targets: 0
                },
                {
                    // For Checkboxes
                    targets: 1,
                    orderable: false,
                    responsivePriority: 3,
                    render: function (data, type, full, meta) {
                        return (
                            '<div class="form-check"> <input class="form-check-input dt-checkboxes" type="checkbox" value="" id="checkbox' +
                            data +
                            '" /><label class="form-check-label" for="checkbox' +
                            data +
                            '"></label></div>'
                        );
                    },
                    checkboxes: {
                        selectAllRender:
                            '<div class="form-check"> <input class="form-check-input" type="checkbox" value="" id="checkboxSelectAll" /><label class="form-check-label" for="checkboxSelectAll"></label></div>'
                    }
                },
                {
                    responsivePriority: 1,
                    targets: 1
                },
                {
                    // Actions
                    targets: 2,
                    orderable: false,
                    render: function (data, type, full, meta) {
                        return DataTablesButtonsRender(data);
                    }
                }
            ],
            order: [[3, 'asc']],
            dom: '<"card-header border-bottom "<"head-label"><"dt-action-buttons text-end "B>><"d-flex  justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            "initComplete": function (settings, json) {

                $('.dt-refresh-button').removeClass('disabled');
                $('.dt-refresh-button').removeAttr('disabled');

                $('.datatable-sys_city').wrap('<div style="overflow-x:auto;"></div>');

                $(".col-vis").on("click", function (e) {
                    e.preventDefault();
                    var count = 0;
                    $(".dt-button-collection div").children().each(function () {
                        if ($(this).text() == "") {
                            if (count == 0) {
                                $(this).text(i18next.t('global.responsive'));
                                ++count;
                            } else {
                                $(this).text(i18next.t('global.select'));
                                ++count;
                            }

                        }
                    });
                });
            },
            responsive: {
                details: {
                    display: $.fn.dataTable.Responsive.display.modal({
                        header: function (row) {
                            var data = row.data();
                            return i18next.t('global.details_of_entry');
                        }
                    }),
                    type: 'column',
                    renderer: function (api, rowIdx, columns) {
                        var data = $.map(columns, function (col, i) {
                            return col.title !== '' // ? Do not show row in modal popup if title is blank (for check box)
                                ? '<tr data-dt-row="' +
                                col.rowIdx +
                                '" data-dt-column="' +
                                col.columnIndex +
                                '">' +
                                '<td>' +
                                col.title +
                                ':' +
                                '</td> ' +
                                '<td>' +
                                col.data +
                                '</td>' +
                                '</tr>'
                                : '';
                        }).join('');

                        return data ? $('<table class="table"/>').append('<tbody>' + data + '</tbody>') : false;
                    }
                }
            },
            displayLength: 10,
            lengthMenu: [10, 25, 50, 75, 100],
            buttons: [
                {
                    className: 'btn btn-outline-secondary  me-2 ms-2 col dt-refresh-button',
                    text: feather.icons["refresh-cw"].toSvg({ class: 'me-50 font-small-4' }) + i18next.t('global.refresh'),
                    init: function (api, node, config) {
                        $(node).removeClass('btn-outline-secondary');
                        $(node).html(feather.icons["refresh-cw"].toSvg({ class: 'me-50 font-small-4' }) + i18next.t('global.refresh'));
                    },
                    action: function (e, dt, node, config) {
                        this.disable();
                        iniTable();
                    }

                },
                {
                    extend: 'colvis',
                    className: 'btn btn-outline-secondary dropdown-toggle col-vis col',
                    text: feather.icons["eye"].toSvg({ class: 'me-50 font-small-4' }) + i18next.t('global.show_columns'),

                    init: function (api, node, config) {
                        $(node).removeClass('btn-outline-secondary');
                        $(node).html(feather.icons["eye"].toSvg({ class: 'me-50 font-small-4' }) + i18next.t('global.show_columns'));
                    }

                },

                {
                    extend: 'collection',
                    className: 'btn btn-outline-secondary dropdown-toggle me-2 ms-2 col',
                    text: feather.icons['share'].toSvg({ class: 'font-small-4 me-50' }) + i18next.t('global.export'),
                    buttons: [
                        {
                            extend: 'print',
                            text: feather.icons['printer'].toSvg({ class: 'font-small-4 me-50' }) + i18next.t('global.print'),
                            className: 'dropdown-item',
                            exportOptions: { columns: ':visible' },
                            init: function (api, node, config) {
                                $(node).removeClass('btn-outline-secondary');
                                $(node).html(feather.icons['printer'].toSvg({ class: 'font-small-4 me-50' }) + i18next.t('global.print'));
                            }
                        },
                        {
                            extend: 'csv',
                            text: feather.icons['file-text'].toSvg({ class: 'font-small-4 me-50' }) + i18next.t('global.csv'),
                            className: 'dropdown-item',
                            exportOptions: { columns: ':visible' },
                            init: function (api, node, config) {
                                $(node).removeClass('btn-outline-secondary');
                                $(node).html(feather.icons['file-text'].toSvg({ class: 'font-small-4 me-50' }) + i18next.t('global.csv'));
                            }
                        },
                        {
                            extend: 'excel',
                            text: feather.icons['file'].toSvg({ class: 'font-small-4 me-50' }) + i18next.t('global.excel'),
                            className: 'dropdown-item',
                            exportOptions: { columns: ':visible' },
                            init: function (api, node, config) {
                                $(node).removeClass('btn-outline-secondary');
                                $(node).html(feather.icons['file'].toSvg({ class: 'font-small-4 me-50' }) + i18next.t('global.excel'));
                            }
                        },
                        {
                            extend: 'pdf',
                            text: feather.icons['clipboard'].toSvg({ class: 'font-small-4 me-50' }) + i18next.t('global.pdf'),
                            className: 'dropdown-item',
                            exportOptions: { columns: ':visible' },
                            init: function (api, node, config) {
                                $(node).removeClass('btn-outline-secondary');
                                $(node).html(feather.icons['clipboard'].toSvg({ class: 'font-small-4 me-50' }) + i18next.t('global.pdf'));
                            }
                        },
                        {
                            extend: 'copy',
                            text: feather.icons['copy'].toSvg({ class: 'font-small-4 me-50' }) + i18next.t('global.copy'),
                            className: 'dropdown-item',
                            exportOptions: { columns: ':visible' },
                            init: function (api, node, config) {
                                $(node).removeClass('btn-outline-secondary');
                                $(node).html(feather.icons['copy'].toSvg({ class: 'font-small-4 me-50' }) + i18next.t('global.copy'));
                            }
                        }


                    ],
                    init: function (api, node, config) {
                        $(node).parent().removeClass('btn-group');
                        $(node).removeClass('btn-outline-secondary');
                        $(node).html(feather.icons['share'].toSvg({ class: 'font-small-4 me-50' }) + i18next.t('global.export'));
                        setTimeout(function () {
                            $(node).closest('.dt-buttons').removeClass('btn-group').addClass('d-inline-flex');
                            $(node).closest('.dt-buttons').addClass("row")
                        }, 50);
                    }
                },
            ],
            language: {
                url: _dataTablesLanguageAssetsPath,
                paginate: {
                    // remove previous & next text from pagination
                    previous: '&nbsp;',
                    next: '&nbsp;'
                }
            }
        });
        dt_basic.on('draw', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
            blockUiRemove();
        });
        dt_basic.on('page.dt', function (e) {
            blockUi(".card");
        });
    } else {
        $('.datatable-sys_city').DataTable().ajax.reload(function (json) {
            $('.dt-refresh-button').removeClass('disabled');
            $('.dt-refresh-button').removeAttr('disabled');
            blockUiRemove();
        });
    }



}


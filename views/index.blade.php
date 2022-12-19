<!-- created using Dev Tools V3 -->

@extends('admin.layout.admin_master_layout')
@section('title',trans('cruds.system_management.sys_city.title'))
@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-start mb-0">{{trans('cruds.system_management.sys_city.title')}}</h2>
                <div class="breadcrumb-wrapper">
                    <ol class="breadcrumb">

                        <li class="breadcrumb-item active">{{trans('cruds.system_management.sys_city.title')}}
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="content-body">
    <div class="row m-1">
        @can('sys_city_create')
        <button type="button" class="btn btn-primary waves-effect waves-float waves-light col-auto m-1" id="btn_add_sys_city">
            <i data-feather="plus"></i>
            <span>{{trans('global.add')}}</span>
        </button>
        @endcan
    </div>
    <section id="basic-datatable">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <table class=" table table-striped datatable-sys_city">
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                                <th data-i18n="global.Actions">{{trans('cruds.system_management.sys_city.fields.Actions')}}</th>
                                <th>{{trans('cruds.system_management.sys_city.fields.id')}}</th>
                                <th>{{trans('cruds.system_management.sys_city.fields.name')}}</th>
                                <th>{{trans('cruds.system_management.sys_city.fields.sys_country_id')}}</th>
                                <th>{{trans('cruds.system_management.sys_city.fields.sys_state_id')}}</th>
                                <th>{{trans('cruds.system_management.sys_city.fields.country_code')}}</th>
                                <th>{{trans('cruds.system_management.sys_city.fields.state_code')}}</th>
                                <th>{{trans('cruds.system_management.sys_city.fields.latitude')}}</th>
                                <th>{{trans('cruds.system_management.sys_city.fields.longitude')}}</th>
                                <th>{{trans('cruds.system_management.sys_city.fields.created_at')}}</th>
                                <th>{{trans('cruds.system_management.sys_city.fields.updated_at')}}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </section>
    @include('admin/pages/system_management/system_initialization/system_entries/sysCity/sys_city_modal/modal_add_sys_city')
    @include('admin/pages/system_management/system_initialization/system_entries/sysCity/components/advance_search_sys_city')

</div>
@endsection
@push('js')
<script src="{{asset('app-js/pages/system_management/system_initialization/system_entries/sys_city/sys_city.js') }}"></script>
<script src="{{asset('app-js/pages/system_management/system_initialization/system_entries/sys_city/sys_city_custom.js') }}"></script>
@endpush
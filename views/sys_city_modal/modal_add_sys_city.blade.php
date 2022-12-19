<!-- created using Dev Tools V3 -->
<x-modal-dialog :modal_name="'sys_city_op_modal'" :modal_size="'modal-lg'" :modal_title="'New sysCity'">


    <form id="sys_city_op_form" class="needs-validation">

        @csrf
        <div class="row">

            <div class="mb-1 col-md-4">
                <label class="form-label  required " for="name">
                    {{trans('cruds.system_management.sys_city.fields.name')}}
                </label>

                <input type="text" class="form-control " id="name" name="name" data-type="text_number_special" data-validation="r/mi_2/mx_255" placeholder="{{trans('cruds.system_management.sys_city.fields.name_helper')}}" required />
                <div class="invalid-feedback"></div>
            </div>
            <div class="mb-1 col-md-4">
                <label class="form-label  required " for="sys_country_id">
                    {{trans('cruds.system_management.sys_city.fields.sys_country_id')}}
                </label>

                <select class="form-select" id="sys_country_id" name="sys_country_id" data-type="number" data-validation="r/miv_1" required>
                </select>
                <div class="invalid-feedback"></div>
            </div>
            <div class="mb-1 col-md-4">
                <label class="form-label " for="sys_state_id">
                    {{trans('cruds.system_management.sys_city.fields.sys_state_id')}}
                </label>

                <select class="form-select" id="sys_state_id" name="sys_state_id" data-type="number" data-validation="o/miv_1">
                </select>
                <div class="invalid-feedback"></div>
            </div>
            <div class="mb-1 col-md-4">
                <label class="form-label " for="latitude">
                    {{trans('cruds.system_management.sys_city.fields.latitude')}}
                </label>

                <input type="text" class="form-control " id="latitude" name="latitude" data-type="number_or_decimal" data-validation="o/miv_1" placeholder="{{trans('cruds.system_management.sys_city.fields.latitude_helper')}}" />
                <div class="invalid-feedback"></div>
            </div>
            <div class="mb-1 col-md-4">
                <label class="form-label " for="longitude">
                    {{trans('cruds.system_management.sys_city.fields.longitude')}}
                </label>

                <input type="text" class="form-control " id="longitude" name="longitude" data-type="number_or_decimal" data-validation="o/miv_1" placeholder="{{trans('cruds.system_management.sys_city.fields.longitude_helper')}}" />
                <div class="invalid-feedback"></div>
            </div>

        </div>

        <button type="submit" id="submit" class="btn btn-primary me-1 sys_city_op_form_submit">{{trans('global.add')}}</button>
        <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{trans('global.cancel')}}</button>
    </form>


</x-modal-dialog>

@push('js')
<script src="{{asset('app-js/pages/system_management/system_initialization/system_entries/sys_city/sys_city_op.js') }}"></script>
@endpush
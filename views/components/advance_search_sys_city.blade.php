<!-- created using Dev Tools V3 -->
<x-advance-search :submit_action="'iniTable()'" :cancel_action="'iniTable()'">
    <form class="dt_adv_search p-1" method="POST">
        <div class="row g-1 mb-md-1">
            <div class="col-md-12">
                <label class="form-label" for="id">{{trans('cruds.system_management.sys_city.fields.id')}}</label>
                <input type="number" class="form-control dt-input " name="id" tabindex="1" />
            </div>
            <div class="col-md-12">
                <label class="form-label" for="name">{{trans('cruds.system_management.sys_city.fields.name')}}</label>
                <input type="text" class="form-control dt-input " name="name" tabindex="2" />
            </div>
            <div class="col-md-12">
                <label class="form-label" for="sys_country_id">{{trans('cruds.system_management.sys_city.fields.sys_country_id')}}</label>
                <select class="form-select dt-input" name="sys_country_id" tabindex="3">
                </select>
            </div>
            <div class="col-md-12">
                <label class="form-label" for="sys_state_id">{{trans('cruds.system_management.sys_city.fields.sys_state_id')}}</label>
                <select class="form-select dt-input" name="sys_state_id" tabindex="4">
                </select>
            </div>
            <div class="col-md-12">
                <label class="form-label" for="country_code">{{trans('cruds.system_management.sys_city.fields.country_code')}}</label>
                <input type="text" class="form-control dt-input " name="country_code" tabindex="5" />
            </div>
            <div class="col-md-12">
                <label class="form-label" for="state_code">{{trans('cruds.system_management.sys_city.fields.state_code')}}</label>
                <input type="text" class="form-control dt-input " name="state_code" tabindex="6" />
            </div>
            <div class="col-md-12">
                <label class="form-label" for="isActive">{{trans('cruds.system_management.sys_city.fields.isActive')}}</label>
                <select class="form-select dt-input" name="isActive" tabindex="7">
                    <option value="">{{trans('global.all')}}</option>
                    @foreach(App\Models\admin\system_management\system_initialization\system_entries\sysCity::_isActiveStats() as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-12">
                <label class="form-label" for="created_at">{{trans('cruds.system_management.sys_city.fields.created_at')}}</label>
                <input type="text" class="form-control dt-date flatpickr-range dt-date-search dt-input" name="created_at" tabindex="8" />
            </div>
            <div class="col-md-12">
                <label class="form-label" for="updated_at">{{trans('cruds.system_management.sys_city.fields.updated_at')}}</label>
                <input type="text" class="form-control dt-date flatpickr-range dt-date-search dt-input" name="updated_at" tabindex="9" />
            </div>
            <button type="button" id="adv_search_btn" class="col-md-4 btn btn-primary me-1 sys_country_op_form_submit">{{trans('global.search')}}</button>
            <button type="button" class="col-md-4 btn btn-outline-secondary adv_cancel_btn">{{trans('global.cancel')}}</button>
        </div>
    </form>
</x-advance-search>
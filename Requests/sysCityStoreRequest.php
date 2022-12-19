<?php

/* created using Dev Tools V3 */

namespace App\Http\Requests\admin\system_management\system_initialization\system_entries;

use App\Models\admin\system_management\system_initialization\system_entries\sysCity;
use App\Http\Requests\FormRequest;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;



class sysCityStoreRequest extends FormRequest
{

    public function authorize()
    {
        if (!Auth::user()->can('sys_city_create')) {
            throw new \Exception(trans('error.permission_can_not'), Response::HTTP_UNAUTHORIZED);
        } else {
            return true;
        }
    }

    public function rules()
    {
        return [

            'name' => [
                'required',
                'string',
                'min:2',
                'max:255',
                function ($attribute, $value, $fail) {
                    $hasItems = sysCity::whereHas('translation', function ($q) use ($value) {
                        $q->where('name', $value)->where('locale', App()->getLocale());
                    })->count() > 0;
                    if ($hasItems) $fail(trans('cruds.system_management.sys_city.fields.name') . trans('error.unique'));
                }
            ],

            'sys_country_id' => [
                'required',
                'string',
            ],

            'sys_state_id' => [
                'nullable',
                'string',
            ],

            'latitude' => [
                'nullable',
                'numeric',
                'min:1',
            ],

            'longitude' => [
                'nullable',
                'numeric',
                'min:1',
            ],

        ];
    }

    public function attributes()
    {
        return [
            'name' => trans('cruds.system_management.sys_city.fields.name'),
            'sys_country_id' => trans('cruds.system_management.sys_city.fields.sys_country_id'),
            'sys_state_id' => trans('cruds.system_management.sys_city.fields.sys_state_id'),
            'latitude' => trans('cruds.system_management.sys_city.fields.latitude'),
            'longitude' => trans('cruds.system_management.sys_city.fields.longitude'),
        ];
    }
}

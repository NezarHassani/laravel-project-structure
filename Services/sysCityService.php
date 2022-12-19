<?php

/* created using Dev Tools V3 */

namespace App\Services\admin\system_management\system_initialization\system_entries;

use App\Models\admin\system_management\system_initialization\system_entries\sysCity;
use App\Models\admin\system_management\system_initialization\system_entries\sysCityTranslation;
use App\Http\Resources\Admin\system_management\system_initialization\system_entries\sysCityTranslationResource;
use App\Http\Resources\Admin\system_management\system_initialization\system_entries\sysCitySelectResource;
use App\Services\admin\system_management\system_initialization\system_entries\sysCountryService;
use App\Services\admin\system_management\system_initialization\system_entries\sysStateService;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class sysCityService
{

    public function getSysCityByUuidOrID($key, $isUuid = false, $validation = ''): sysCity
    {
        $relations = [
            'translation',
            'sysCountry_r.translations',
            'sysState_r.translations'
        ];
        $SysCity = sysCity::when(count($relations) != 0, function ($q) use ($relations) {
                $q->with($relations);
            })
            ->where($isUuid ? 'uuid' : 'id', $key)->withTranslation()->first();
        if ($SysCity->count() == 0) {
            if ($validation === '') {
                throw new \Exception(trans('error.data_not_found'), Response::HTTP_NOT_FOUND);
            } else {
                throw new \Exception(serialize(['msg' => trans('error.data_not_found'), 'attribute' => $validation]), 422);
            }
        }
        return $SysCity;
    }

    public function prepareSysCityForCreate($sysCityData): sysCity
    {
        if (!Auth::user()->can('sys_city_create')) {
            throw new \Exception(trans('error.permission_can_not'), Response::HTTP_UNAUTHORIZED);
        }

        $SysCityUuid =  (string) Str::uuid();
        $sys_country_id_select = (new sysCountryService())->getSysCountryByUuidOrID($sysCityData['sys_country_id'], true, 'sys_country_id')->id;
        $sys_state_id_select = $sysCityData['sys_state_id'] == null ? null : (new sysStateService())->getSysStateByUuidOrID($sysCityData['sys_state_id'], true, 'sys_state_id')->id;
        $SysCity = $this->createSysCity([
            'uuid' => $SysCityUuid,
            'sys_country_id' => $sys_country_id_select,
            'sys_state_id' => $sys_state_id_select,
            'latitude' => $sysCityData['latitude'],
            'longitude' => $sysCityData['longitude'],
            'created_at' => Carbon::now()
        ]);
        $this->createTranslation([
            'sys_city_id' => $SysCity->id,
            'name' => $sysCityData['name'],
            'locale' => App()->getLocale(),
        ]);

        return $SysCity;
    }
    private function createSysCity($SysCity): sysCity
    {
        $SysCity = sysCity::create($SysCity);
        return $SysCity;
    }
    private function createTranslation($sysCityTranslation): sysCityTranslation
    {
        $sysCityTranslation = sysCityTranslation::create($sysCityTranslation);
        return $sysCityTranslation;
    }
    public function prepareSysCityForUpdate($sysCityData): sysCity
    {

        if (!Auth::user()->can('sys_city_edit')) {
            throw new \Exception(trans('error.permission_can_not'), Response::HTTP_UNAUTHORIZED);
        }
        $SysCity = '';
        if (isset($sysCityData['sys_city_id'])) {
            $SysCity =  sysCity::where('uuid', $sysCityData['sys_city_id'])->get()->first();
            if ($SysCity == null) {
                throw new \Exception(trans('error.data_not_found'), Response::HTTP_NOT_FOUND);
            }
        } else {
            throw new \Exception(trans('error.request_missing_data'), 404);
        }
        $sys_country_id_select = (new sysCountryService())->getSysCountryByUuidOrID($sysCityData['sys_country_id'], true, 'sys_country_id')->id;
        $sys_state_id_select = $sysCityData['sys_state_id'] == null ? null : (new sysStateService())->getSysStateByUuidOrID($sysCityData['sys_state_id'], true, 'sys_state_id')->id;

        $SysCity->sys_country_id = $sys_country_id_select;
        $SysCity->sys_state_id = $sys_state_id_select;
        $SysCity->latitude = $sysCityData['latitude'];
        $SysCity->longitude = $sysCityData['longitude'];

        $SysCity->updated_at = Carbon::now();
        $SysCity = $this->UpdateSysCity($SysCity);

        $this->updateTranslation([
            'sys_city_id' => $SysCity->id,
            'name' => $sysCityData['name'],
            'locale' => App()->getLocale(),
        ]);
        return $SysCity;
    }
    private function UpdateSysCity(sysCity $sysCityData): sysCity
    {
        $sysCityData->update();
        return $sysCityData;
    }
    private function updateTranslation($sysCityData): sysCityTranslation
    {
        $sysCityData = sysCityTranslation::updateOrCreate(
            ['sys_city_id' => $sysCityData['sys_city_id'], 'locale' => $sysCityData['locale']],
            $sysCityData
        );
        return $sysCityData;
    }
    public function prepareTranslationForExternalUpdate($sysCityData): sysCityTranslation
    {
        if (!Auth::user()->can('sys_city_translate_e')) {
            throw new \Exception(trans('error.permission_can_not'), Response::HTTP_UNAUTHORIZED);
        }

        $SysCity = '';
        if (isset($sysCityData['translatable'])) {
            $SysCity =  sysCity::where('uuid', $sysCityData['translatable'])->get()->first();
            if ($SysCity == null) {
                throw new \Exception(trans('error.data_not_found'), Response::HTTP_NOT_FOUND);
            }
        } else {
            throw new \Exception(trans('error.request_missing_data'), 404);
        }

        $SysCityTranslation = $this->updateTranslation([
            'sys_city_id' => $SysCity->id,
            'name' => $sysCityData['name'],
            'locale' => $sysCityData['locale'],
        ]);

        return $SysCityTranslation;
    }
    public function prepareSysCityForDelete($sysCityData): bool
    {
        if (!Auth::user()->can('sys_city_delete')) {
            throw new \Exception(trans('error.permission_can_not'), Response::HTTP_UNAUTHORIZED);
        }

        $sysCity = '';
        if (isset($sysCityData['sys_city_id'])) {
            $sysCity =  sysCity::where('uuid', $sysCityData['sys_city_id'])->get()->first();
            if ($sysCity == null) {
                throw new \Exception(trans('error.data_not_found'), Response::HTTP_NOT_FOUND);
            }
        } else {
            throw new \Exception(trans('error.request_missing_data'), 404);
        }

        if ($sysCity->secureDelete($sysCity->getAllRelation())) {
            throw new \Exception(trans('error.data_can_not_delete'), Response::HTTP_BAD_REQUEST);
        }


        if ($this->prepareTranslationForDelete(['sys_city_id' => $sysCity->id], true)) {
            return $this->deleteSysCity($sysCity);
        }

        return false;
    }
    private function deleteSysCity(sysCity $sysCity): bool
    {
        return $sysCity->delete();
    }
    private function prepareTranslationForDelete($sysCityData, $all = false): bool
    {

        if (!Auth::user()->can('sys_city_translate_d')) {
            throw new \Exception(trans('error.permission_can_not'), Response::HTTP_UNAUTHORIZED);
        }
        if ($all) {
            $translations = sysCityTranslation::where('sys_city_id', $sysCityData['sys_city_id'])->get();
            if ($translations->count() > 1) {
                $first = $translations->first();
                foreach ($translations->where('id', '<>', $first->id) as $item) {
                    $this->deleteTranslation($item);
                }
            }
            return true;
        } else {
            $translation = sysCityTranslation::find($sysCityData['id']);

            if ($translation == null) {
                throw new \Exception(trans('error.data_not_found'), Response::HTTP_NOT_FOUND);
            }
            $translations = sysCityTranslation::where('sys_city_id', $translation->sys_city_id)->get();

            if ($translations->count() == 1) {
                throw new \Exception(trans('error.translation_delete_only_on'), Response::HTTP_BAD_REQUEST);
            } else {
                return $translation->delete();
            }
        }
    }
    private function deleteTranslation(sysCityTranslation $sysCityData): bool
    {
        return $sysCityData->delete();
    }
    public function prepareTranslationForExternalDelete($sysCityData): bool
    {

        if (!Auth::user()->can('sys_city_translate_d')) {
            throw new \Exception(trans('error.permission_can_not'), Response::HTTP_UNAUTHORIZED);
        }

        $sysCity = '';
        if (isset($sysCityData['translatable'])) {
            $sysCity =  sysCity::withTranslation()->where('uuid', $sysCityData['translatable'])->get()->first();
            if ($sysCity == null) {
                throw new \Exception(trans('error.data_not_found'), Response::HTTP_NOT_FOUND);
            }
        } else {
            throw new \Exception(trans('error.request_missing_data'), 404);
        }

        if ($sysCity->translations->where('locale', $sysCityData['locale'])->count() == 0) {
            throw new \Exception(trans('error.translation_not_found'), Response::HTTP_NOT_FOUND);
        }

        if ($sysCity->translations->count() == 1) {
            throw new \Exception(trans('error.translation_delete_only_on'), Response::HTTP_BAD_REQUEST);
        } else {
            return $sysCity->translations->where('locale', $sysCityData['locale'])->first()->delete();
        }
    }
    public function getTranslationSettings($sysCity_uuid)
    {
        $data['headers'] = [
            trans('global.locale'),
            trans('cruds.system_management.sys_city.fields.name'),
            trans('global.actions'),
        ];

        $data['fields'] = [
            'name' => ['data-type' => 'text_number_special', 'data-validation' => 'r/mi_2/mx_255', 'type' => 'T'],
            'Actions' => ['data' => Auth::user()->can('sys_city_translate_c'), 'type' => 'B'],
        ];
        $sysCity = $this->getSysCityByUuidOrID($sysCity_uuid, true);
        $data['locale'] = trans('lang.lang');

        $data['data'] = sysCityTranslationResource::collection(sysCityTranslation::where('sys_city_id', $sysCity->id)->get());
        $data['crud'] = sysCity::$translation_crud;

        return $data;
    }
    public function propertyUpdate($sysCityData): sysCity
    {

        if (!Auth::user()->can('sys_city_edit')) {
            throw new \Exception(trans('error.permission_can_not'), Response::HTTP_UNAUTHORIZED);
        }
        $sysCity = '';
        if (isset($sysCityData['sys_city_id'])) {
            $sysCity =  sysCity::where('uuid', $sysCityData['sys_city_id'])->get()->first();
            if ($sysCity == null) {
                throw new \Exception(trans('error.data_not_found'), Response::HTTP_NOT_FOUND);
            }
        } else {
            throw new \Exception(trans('error.request_missing_data'), 404);
        }

        $proerty = $sysCityData['property'];
        $sysCity->$proerty = $sysCityData['value'];
        $sysCity->updated_at = Carbon::now();
        $sysCity = $this->UpdateSysCity($sysCity);

        return $sysCity;
    }
    public function getList($search, $column, $hasDependence, $dependence)
    {
        $query = sysCity::where('isActive', 1)->whereHas('translations', function ($q) use ($search, $column,) {
            return $q->where($column, 'like', "%" . $search . "%");
        })
            ->when($hasDependence != null && $dependence != null, function ($q) use ($hasDependence, $dependence) {
                $q->whereHas($dependence, function ($d_q) use ($hasDependence) {
                    $d_q->where('uuid', $hasDependence);
                });
            })->limit(10)->get();
        $data = sysCitySelectResource::collection($query);
        return $data;
    }
}
